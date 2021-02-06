--------------------------------------------------------
--  File created - Friday-November-15-2019   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Package POSTDOC_METADATA
--------------------------------------------------------

create or replace PACKAGE POSTDOC_METADATA AS 
    procedure gather_table_metadata (
        p_table_name in varchar2,
        p_ds_id in number default null,
        p_so_id in number default null,
        p_hl_id in number default null,
        p_ds_desc in varchar2 default null,
        p_velocity_id in varchar2 default null,
        p_role_id in varchar2 default null,
        p_formattype_id in varchar2 default null,
        p_freq in varchar2 default null,
        p_usermail in varchar2 default null
        );
        
    procedure gather_xml_metadata (
        p_spec in varchar2,
        p_ds_id in number default null,
        p_so_id in number default null,
        p_hl_id in number default null,
        p_ds_desc in varchar2 default null,
        p_velocity_id in varchar2 default null,
        p_role_id in varchar2 default null,
        p_formattype_id in varchar2 default null,
        p_freq in varchar2 default null,
        p_usermail in varchar2 default null         
    );

  procedure compare_table_structure (
        p_table_name in varchar2,
        p_ds_id in number default null
    );

  function is_xml_uploaded (p_spec in varchar2) return number;  

END POSTDOC_METADATA;
/


create or replace PACKAGE BODY POSTDOC_METADATA AS

  TYPE t_cons_cols IS TABLE OF all_cons_columns%rowtype index by binary_integer;
  TYPE t_meta_prop IS varray(5) of varchar2(100);
  
  c_type_col varchar2(10):='DIT0000001';
  c_type_relFK varchar2(10):='RLT0000002';
  c_type_relCom varchar2(10):='RLT0000001';
  c_type_xmlelem varchar2(10):='DIT0000002';
  c_type_xmlattr varchar2(10):='DIT0000003';
  c_meta_prop t_meta_prop := t_meta_prop('DATA_TYPE', 'DATA_LENGTH', 'DATA_PRECISION', 'DATA_SCALE', 'NULLABLE');
  
  function insert_column_metadata (v_tab_col in all_tab_columns%rowtype, v_ds_id number) return number AS
    v_di_id number(10);
    begin
        insert into dataitem (di_name, di_dataset_id, di_itemtype_id) values (v_tab_col.column_name, v_ds_id, c_type_col);
        select dataitem_di_id_seq.currval into v_di_id from dual;
        -- metadata properties
        insert into metadataProperty (md_name, md_value, md_dataitem_id) values (c_meta_prop(1),v_tab_col.DATA_TYPE,v_di_id);
        insert into metadataProperty (md_name, md_value, md_dataitem_id) values (c_meta_prop(2),v_tab_col.DATA_LENGTH,v_di_id);
        if v_tab_col.data_precision is not null then
            insert into metadataProperty (md_name, md_value, md_dataitem_id) values (c_meta_prop(3),v_tab_col.DATA_PRECISION,v_di_id);
        end if;
        if v_tab_col.data_scale is not null then
            insert into metadataProperty (md_name, md_value, md_dataitem_id) values (c_meta_prop(4),v_tab_col.DATA_SCALE,v_di_id);
        end if;
        insert into metadataProperty (md_name, md_value, md_dataitem_id) values (c_meta_prop(5),v_tab_col.NULLABLE,v_di_id);
        return v_di_id;
  end insert_column_metadata;
  
  function get_ds_id_for_table (p_owner varchar2, p_table_name varchar2, p_so_id number default null, p_hl_id number default null) return number as
    v_ds_id number(10);
  begin
    select ds_id into v_ds_id from dataset 
      where (ds_datasource_id=p_so_id and p_hl_id is null or ds_datahighwaylevel_id=p_hl_id and p_so_id is null) and ds_name=upper(p_owner||'.'||p_table_name);
    return v_ds_id;
  exception when no_data_found then
    return 0;
  end get_ds_id_for_table;
  
  function get_di_id_for_column (
    p_owner varchar2, 
    p_table_name varchar2, 
    p_column_name varchar2, 
    p_so_id number default null, 
    p_hl_id number default null, 
    p_ds_id number default null
    ) return number as
    
    v_ds_id number(10):=p_ds_id;
    v_di_id number(10);
  begin
    if p_ds_id is null then 
      v_ds_id:=get_ds_id_for_table(p_owner, p_table_name, p_so_id, p_hl_id);
    end if;
    if v_ds_id<>0 then
      begin
        select di_id into v_di_id from dataitem where di_dataset_id=v_ds_id and di_name=upper(p_column_name);
        return v_di_id;
      exception when no_data_found then
        return 0;
      end;
    else
      return 0;
    end if;
  end get_di_id_for_column;
  
  procedure insert_constraint_metadata (p_cons ALL_CONSTRAINTS%rowtype, p_ds_id number default null, p_so_id number default null, p_hl_id number default null) AS 
  -- all FK constraints incoming and outcoming must be gathered. if a ref table is not in metadata, skip
    v_owner varchar2(100):=p_cons.owner;
    v_table_name varchar2(100):=p_cons.table_name;
    v_ds_id number(10):=p_ds_id;
    v_ref_cons ALL_CONSTRAINTS%rowtype;
    v_cons_cols t_cons_cols;
    v_ref_cons_cols t_cons_cols;
    v_ref_ds_id number(10);
    v_di_id number(10);
    v_ref_di_id number(10);
    v_rl_id number(10);
    v_flag number(1);
  begin
    if p_ds_id is null then
      v_ds_id:=get_ds_id_for_table(v_owner, v_table_name, p_so_id, p_hl_id);
    end if;
    if v_ds_id is not null and v_ds_id<>0 then
        if p_cons.constraint_type='C' and not regexp_like(upper(p_cons.search_condition_vc),'^"+[a-zA-Z0-9_]+" IS NOT NULL$') then -- check constraint excluding not null
          insert into metadataProperty (md_name, md_value, md_dataset_id) values ('CHECK_CONSTRAINT',p_cons.search_condition_vc,v_ds_id);
        elsif p_cons.constraint_type='R' then -- FK
          begin
          select * into v_ref_cons from ALL_CONSTRAINTS where constraint_name=p_cons.r_constraint_name and owner=p_cons.r_owner;
          v_ref_ds_id:=get_ds_id_for_table(v_ref_cons.owner, v_ref_cons.table_name, p_so_id, p_hl_id);
          if v_ref_ds_id<>0 then
            select * bulk collect into v_cons_cols from all_cons_columns where owner=v_owner and constraint_name=p_cons.constraint_name order by position;
            select * bulk collect into v_ref_cons_cols from all_cons_columns where owner=v_owner and constraint_name=v_ref_cons.constraint_name order by position;
            for i in v_cons_cols.first..v_cons_cols.last loop
              v_di_id:=get_di_id_for_column(v_owner, v_table_name, v_cons_cols(i).column_name, p_so_id, p_hl_id, v_ds_id);
              v_ref_di_id:=get_di_id_for_column(v_ref_cons.owner, v_ref_cons.table_name, v_ref_cons_cols(i).column_name, p_so_id, p_hl_id, v_ref_ds_id);
              select case when exists (
                select 1 from relationship r join relationshipelement re on re.re_relationship_id=r.rl_id 
                where r.rl_parent_dataitem_id=v_ref_di_id and re.re_child_dataitem_id=v_di_id and r.rl_relationshiptype_id=c_type_relFK)
                then 1 else 0 end into v_flag from dual;
              if v_flag=0 then
                insert into relationship (rl_parent_dataitem_id, rl_relationshiptype_id) values (v_ref_di_id, c_type_relFK);
                select RELATIONSHIP_RL_ID_SEQ.currval into v_rl_id from dual;
                insert into relationshipelement values (v_di_id, v_rl_id);
              end if;
            end loop;
          end if;
          exception when no_data_found then
            insert into error_log values (sysdate,'p_cons.r_constraint_name='||p_cons.r_constraint_name); 
          end;
        end if;
    end if;
  end insert_constraint_metadata;
  
  function get_author_id_by_usermail (p_usermail in varchar2) return number as
    v_au_id number(10);
    v_us_id number(10):=null;
    v_username varchar2(100);
  begin
    select au_id into v_au_id from author join user_tab on au_user_id=us_id where us_email=p_usermail;
    return v_au_id;
  exception when no_data_found then
    select us_id, us_name into v_us_id, v_username from user_tab where us_email = p_usermail;
    insert into author values (AUTHOR_AU_ID_SEQ.nextval, v_username, v_us_id);
    select AUTHOR_AU_ID_SEQ.currval into v_au_id from dual;  
    return v_au_id;
  end get_author_id_by_usermail;
  
  function insert_dataset (
        p_name in varchar2, 
        p_desc in varchar2 default null, 
        p_so_id number default null, 
        p_hl_id number default null, 
        p_velocity_id varchar2 default null, 
        p_role_id varchar2 default null, 
        p_formattype_id in varchar2 default null,
        p_freq in varchar2 default null,
        p_usermail in varchar2 default null
        ) return number as
        
        v_ds_id number(10);
        v_au_id number(10):=null;
        v_ch_id number(10);
        v_ch_type types.tp_id%type;
        v_st_type types.tp_id%type;
    begin
        insert into dataset (ds_name, ds_description, ds_datasource_id, ds_velocity_id, ds_role_id, ds_datahighwaylevel_id, ds_formattype_id, ds_frequency, ds_created)
          values (upper(p_name), p_desc, p_so_id, p_velocity_id, p_role_id, p_hl_id, p_formattype_id, p_freq, sysdate);
        select dataset_ds_id_seq.currval into v_ds_id from dual;
        if p_usermail is not null then
          v_au_id:=get_author_id_by_usermail(p_usermail);
        end if;
        select tp_id into v_ch_type from types where tp_type='Addition';
        select tp_id into v_st_type from types where tp_type='New';
        insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATASET_ID, CH_AUTHOR_ID, CH_DATETIME) 
                    values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_ds_id, v_au_id, sysdate);
        commit;
        return v_ds_id;
    end insert_dataset;

  procedure gather_table_metadata (
        p_table_name in varchar2,
        p_ds_id in number default null,
        p_so_id in number default null,
        p_hl_id in number default null,
        p_ds_desc in varchar2 default null,
        p_velocity_id in varchar2 default null,
        p_role_id in varchar2 default null,
        p_formattype_id in varchar2 default null,
        p_freq in varchar2 default null,
        p_usermail in varchar2 default null
        ) AS
        
        v_ds_id number(10):=p_ds_id;
        v_di_id number(10);
        v_owner varchar2(100):=substr(upper(p_table_name),1,instr(p_table_name,'.')-1);
        v_table varchar2(100):=substr(upper(p_table_name),instr(p_table_name,'.')+1);
  BEGIN
    if p_ds_id is not null or (p_hl_id is not null or p_so_id is not null) and p_velocity_id is not null and p_formattype_id is not null then
        if p_ds_id is null then -- no existing data set, must create one
            v_ds_id:=insert_dataset(p_table_name, p_ds_desc, p_so_id, p_hl_id, p_velocity_id, p_role_id, p_formattype_id, p_freq, p_usermail);
        end if;
        for v_tab_col in (select * from all_tab_columns where owner=v_owner and table_name = v_table) loop
            v_di_id:=insert_column_metadata(v_tab_col, v_ds_id);
        end loop;
        -- outgoing constraints
        for v_cons in (select * from ALL_CONSTRAINTS where owner=v_owner and table_name = v_table) loop
            insert_constraint_metadata (v_cons, v_ds_id, p_so_id, p_hl_id);
        end loop;
        -- incoming constraints
        for v_cons in (select  r.* from ALL_CONSTRAINTS c join ALL_CONSTRAINTS r on r.r_constraint_name=c.constraint_name and r.r_owner=c.owner 
            where c.owner=v_owner and c.table_name=v_table and c.constraint_type in ('P', 'U')) loop
            insert_constraint_metadata (v_cons, null, p_so_id, p_hl_id);            
        end loop;
    end if;
  END gather_table_metadata;
  
  procedure property_value_change (p_md_id in number, p_old in varchar2, p_new in varchar2, p_attrname in varchar2, p_usermail in varchar2 default null) as
    v_au_id number(10):=null;
    v_ch_type types.tp_id%type;
    v_st_type types.tp_id%type;
  begin
    if p_usermail is not null then
      v_au_id:=get_author_id_by_usermail(p_usermail);
    end if;
    select tp_id into v_ch_type from types where tp_type='Metadata value update';
    select tp_id into v_st_type from types where tp_type='New';
    update metadataproperty set md_value=p_new where md_id=p_md_id;
    insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_METADATAPROPERTY_ID, CH_AUTHOR_ID, CH_DATETIME, CH_NEWATTRVALUE, CH_OLDATTRVALUE, CH_ATTRNAME) 
                values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, p_md_id, v_au_id, sysdate, p_new, p_old, p_attrname);
    commit;
  end property_value_change;
  
  procedure compare_column_metadata(p_tab_col in all_tab_columns%rowtype, p_ds_id number) AS
    v_di dataitem%rowtype;
    v_di_id number(10);
    v_count number(10);
    v_ch_type types.tp_id%type;
    v_st_type types.tp_id%type;
  begin
    select count(*) into v_count from dataitem where di_dataset_id=p_ds_id and di_name=p_tab_col.column_name;
    if v_count=0 then -- data item is new
      v_di_id:=insert_column_metadata (p_tab_col, p_ds_id);
      select tp_id into v_ch_type from types where tp_type='Addition';
      select tp_id into v_st_type from types where tp_type='New';
      insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME) 
                  values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_di_id, sysdate);
    else -- data item is present in metadata
      select * into v_di from dataitem where di_dataset_id=p_ds_id and di_name=p_tab_col.column_name;
      -- compare metadata properties
      for v_meta in (select * from metadataProperty where md_dataitem_id=v_di.di_id and md_deleted is null -- selecting properties that are already in metadata
                     and md_name in (select tp_type from types where tp_parenttype_id='MPR0000000')) loop
        case (v_meta.md_name) 
        when c_meta_prop(1) then 
          if p_tab_col.DATA_TYPE<>v_meta.md_value then -- change in metadata property value
            property_value_change(v_meta.md_id, v_meta.md_value, p_tab_col.DATA_TYPE, c_meta_prop(1));
          end if;
        when c_meta_prop(2) then 
          if p_tab_col.DATA_LENGTH<>v_meta.md_value then -- change in metadata property value
            property_value_change(v_meta.md_id, v_meta.md_value, p_tab_col.DATA_LENGTH, c_meta_prop(1));
          end if;
        when c_meta_prop(3) then 
          if p_tab_col.DATA_PRECISION<>v_meta.md_value then -- change in metadata property value
            property_value_change(v_meta.md_id, v_meta.md_value, p_tab_col.DATA_PRECISION,c_meta_prop(1));
          end if;
        when c_meta_prop(4) then 
          if p_tab_col.DATA_SCALE<>v_meta.md_value then -- change in metadata property value
            property_value_change(v_meta.md_id, v_meta.md_value, p_tab_col.DATA_SCALE, c_meta_prop(1));
          end if;
        when c_meta_prop(5) then 
          if p_tab_col.NULLABLE<>v_meta.md_value then -- change in metadata property value
            property_value_change(v_meta.md_id, v_meta.md_value, p_tab_col.NULLABLE, c_meta_prop(1));
          end if;
        end case;
      end loop;
    end if;
    commit;      
  end compare_column_metadata;
  
  procedure compare_table_structure (
        p_table_name in varchar2,
        p_ds_id in number default null
        ) AS

        v_owner varchar2(100):=substr(upper(p_table_name),1,instr(p_table_name,'.')-1);
        v_table varchar2(100):=substr(upper(p_table_name),instr(p_table_name,'.')+1);
  begin
    if p_ds_id is not null then
        for v_tab_col in (select * from all_tab_columns where owner=v_owner and table_name = v_table) loop
            compare_column_metadata(v_tab_col, p_ds_id);
        end loop;
        -- compare constraints
    end if;
  end compare_table_structure;        
  
  function insert_xml_children_metadata (p_spec varchar2, p_item luadm.xml_nodes%rowtype, p_ds_id in number, p_parent_di_id in number) return number as
    v_di_id number(10);
    v_child_di_id number(10);
    v_itemtype varchar2(10);
    v_rl_id number(10);
    begin
      if p_item.typ='e' then
        v_itemtype:=c_type_xmlelem;
      else
        v_itemtype:=c_type_xmlattr;
      end if;
      insert into dataitem (di_name, di_dataset_id, di_itemtype_id) values (p_item.name, p_ds_id, v_itemtype);
      select dataitem_di_id_seq.currval into v_di_id from dual;
      if p_parent_di_id is not null then
        insert into Relationship (rl_parent_dataitem_id, rl_relationshiptype_id) values (p_parent_di_id, c_type_relCom);
        select RELATIONSHIP_RL_ID_SEQ.currval into v_rl_id from dual;
        insert into relationshipelement values (v_di_id, v_rl_id);
      end if;
      if v_itemtype=c_type_xmlelem then
        for v_child in (select * from luadm.xml_nodes where lower(spec) like '%'||lower(p_spec) and prev=p_item.id) loop
          v_child_di_id:=insert_xml_children_metadata(p_spec, v_child, p_ds_id, v_di_id);
        end loop;
      end if;
      return v_di_id;
  end insert_xml_children_metadata;

  procedure gather_xml_metadata (
        p_spec in varchar2,
        p_ds_id in number default null,
        p_so_id in number default null,
        p_hl_id in number default null,
        p_ds_desc in varchar2 default null,
        p_velocity_id in varchar2 default null,
        p_role_id in varchar2 default null,
        p_formattype_id in varchar2 default null,
        p_freq in varchar2 default null,
        p_usermail in varchar2 default null        
    ) AS
    v_ds_id number(10):=p_ds_id;
    v_di_id number(10);
  begin
    if p_ds_id is not null or (p_hl_id is not null or p_so_id is not null) and p_velocity_id is not null and p_formattype_id is not null then
        if p_ds_id is null then -- no existing data set, must create one
            v_ds_id:=insert_dataset(p_spec, p_ds_desc, p_so_id, p_hl_id, p_velocity_id, p_role_id, p_formattype_id, p_freq, p_usermail);
        end if;
        for v_item in (select * from luadm.xml_nodes where lower(spec) like '%'||lower(p_spec) and prev is null) loop
          v_di_id:=insert_xml_children_metadata(p_spec, v_item, v_ds_id, null);
        end loop;
    end if;
  end gather_xml_metadata;
  
  procedure mark_children_deleted(p_di_id in dataitem.di_id%type) as
  begin
    update dataitem set di_deleted=sysdate where di_id=p_di_id;
    for v_child_di in (select re.re_child_dataItem_id 
        from relationship r join relationshipElement re on re.re_relationship_id=r.rl_id 
        where r.rl_parent_dataItem_ID=p_di_id and r.rl_relationshipType_id=c_type_relCom) loop
      mark_children_deleted(v_child_di.re_child_dataItem_id);
    end loop;
  end mark_children_deleted;
  
  procedure compare_xml_children_metadata (p_spec varchar2, p_item xml_nodes_copy%rowtype, p_ds_id in number, p_parent_di_id in number) as
    v_di_id dataitem.di_id%type;
    v_ch_type types.tp_id%type;
    v_st_type types.tp_id%type;
    v_child_di_id dataitem.di_id%type;
    v_new number(1):=0;
  begin 
    -- check if such data item with the parent and type is present 
    begin
      select di_id into v_di_id from dataitem d where di_name=p_item.name and di_dataset_id=p_ds_id 
        and di_itemtype_id=decode(p_item.typ,'e',c_type_xmlelem,c_type_xmlattr) 
        and (p_parent_di_id is null 
            and not exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null)
          or p_parent_di_id is not null 
            and exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null
                and r.rl_parent_dataItem_id=p_parent_di_id));
                
      for v_child in (select * from xml_nodes_copy where lower(spec) like '%'||lower(p_spec) and prev=p_item.id) loop
        compare_xml_children_metadata(p_spec, v_child, p_ds_id, v_di_id);
      end loop;                      
    exception when no_data_found then -- not present, something changed
    -- check if type changed
    if p_item.typ='e' then 
      begin
        select di_id into v_di_id from dataitem d where di_name=p_item.name and di_dataset_id=p_ds_id 
          and di_itemtype_id=c_type_xmlattr
          and (p_parent_di_id is null 
              and not exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                  and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null)
            or p_parent_di_id is not null 
              and exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                  and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null
                  and r.rl_parent_dataItem_id=p_parent_di_id));    
        -- change of type from attribute to element
        update dataitem set di_itemtype_id=c_type_xmlelem where di_id=v_di_id;
        select tp_id into v_ch_type from types where tp_type='Metadata value update';
        select tp_id into v_st_type from types where tp_type='New';
        insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME, CH_NEWATTRVALUE, CH_OLDATTRVALUE, CH_ATTRNAME) 
                values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_di_id, sysdate, c_type_xmlelem, c_type_xmlattr, 'DI_ITEMTYPE_ID');        
        commit;
        -- process children as new data items
        for v_child in (select * from xml_nodes_copy where lower(spec) like '%'||lower(p_spec) and prev=p_item.id) loop
          v_child_di_id:=insert_xml_children_metadata(p_spec, v_child, p_ds_id, v_di_id);
          select tp_id into v_ch_type from types where tp_type='Addition';
          select tp_id into v_st_type from types where tp_type='New';
          insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME) 
                  values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_child_di_id, sysdate);        
          commit;          
        end loop;        
      exception when no_data_found then
        v_new:=1;
      end;
    else
      begin
        select di_id into v_di_id from dataitem d where di_name=p_item.name and di_dataset_id=p_ds_id 
          and di_itemtype_id=c_type_xmlattr
          and (p_parent_di_id is null 
              and not exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                  and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null)
            or p_parent_di_id is not null 
              and exists (select 1 from relationshipelement re join relationship r on re.re_relationship_id=r.rl_id 
                  and re.re_child_dataItem_ID=d.di_id and r.rl_relationshipType_id=c_type_relCom and r.rl_deleted is null
                  and r.rl_parent_dataItem_id=p_parent_di_id));    
        -- change of type from element to attribute
        update dataitem set di_itemtype_id=c_type_xmlelem where di_id=v_di_id;
        select tp_id into v_ch_type from types where tp_type='Metadata value update';
        select tp_id into v_st_type from types where tp_type='New';
        insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME, CH_NEWATTRVALUE, CH_OLDATTRVALUE, CH_ATTRNAME) 
                values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_di_id, sysdate, c_type_xmlattr, c_type_xmlelem, 'DI_ITEMTYPE_ID');        
        commit;
        -- process previously existing children as deleted
        for v_child_di in (select re.re_child_dataItem_id 
            from relationship r join relationshipElement re on re.re_relationship_id=r.rl_id 
            where r.rl_parent_dataItem_ID=v_di_id and r.rl_relationshipType_id=c_type_relCom) loop
          
          select tp_id into v_ch_type from types where tp_type='Deletion';
          select tp_id into v_st_type from types where tp_type='New';
          insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME) 
                values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_child_di.re_child_dataItem_id, sysdate);        
          commit;
          mark_children_deleted(v_child_di.re_child_dataItem_id);          
        end loop;
      exception when no_data_found then
        v_new:=1;
      end;
    end if;
    if v_new=1 then
      v_di_id:=insert_xml_children_metadata(p_spec, p_item, p_ds_id, p_parent_di_id);
      select tp_id into v_ch_type from types where tp_type='Addition';
      select tp_id into v_st_type from types where tp_type='New';
      insert into change (CH_ID, CH_CHANGETYPE_ID, CH_STATUSTYPE_ID, CH_DATAITEM_ID, CH_DATETIME) 
              values (CHANGE_CH_ID_SEQ.nextval, v_ch_type, v_st_type, v_di_id, sysdate);        
      commit;       
    end if;
    end;
  end;
  
  procedure compare_xml_metadata (
        p_spec in varchar2,
        p_ds_id in number default null,
        p_usermail in varchar2 default null   
    ) AS 
  
  begin
     for v_item in (select * from xml_nodes_copy where lower(spec) like '%'||lower(p_spec) and prev is null) loop
       compare_xml_children_metadata(p_spec, v_item, p_ds_id, null);
     end loop;
  end;
  
  function is_xml_uploaded (p_spec in varchar2) return number as
    v_cnt number(1);
  begin
    select count(*) into v_cnt from luadm.xml_nodes where lower(spec) like '0 '||lower(p_spec) and rownum=1;
    return v_cnt;
  end is_xml_uploaded;

END POSTDOC_METADATA;
/