--------------------------------------------------------
--  DDL for Package change_adaptation
--------------------------------------------------------

create or replace package change_adaptation as

  function define_change_type(in_change in change%rowtype) return types.tp_id%type;

  procedure create_change_adaptation_proc;

  function get_change_adaptation_scenario(in_change_id in change.ch_id%type) return sys_refcursor;

  procedure adapt_changes;

  function dataset_example_added(in_change_id in change.ch_id%type) return boolean;

  procedure set_process_adapted(in_process_id in changeadaptationprocess.cap_id%type);

  procedure run_change_adaptation_scenario(in_change_id in change.ch_id%type);

  procedure set_manual_condition_fulfilled(in_change_id in change.ch_id%type,
                                           in_condition_id in changeadaptationcondition.cac_id%type);
                                           
  procedure rename_dhighlevel(in_change_id in change.ch_id%type);                                           

  procedure add_dataset_example(in_change_id in change.ch_id%type,
                                in_data_type in types.tp_id%type,
                                in_data      in changeadaptationadditionaldata.caad_data%type);

  procedure get_dataset_structure(in_change_id in change.ch_id%type);
  
  procedure add_dataset_to_1st_dhighlevel(in_change_id in change.ch_id%type);
  
  procedure add_dataitem_to_1st_dhighlevel(in_change_id in change.ch_id%type);

  function dataitem_added_to_datasource (in_change_id in change.ch_id%type) return boolean;
  
  function dataitem_added_to_dhlevel (in_change_id in change.ch_id%type) return boolean;

  function dataitem_from_datasource (in_change_id in change.ch_id%type) return boolean;

  function dataitem_from_dhlevel (in_change_id in change.ch_id%type) return boolean;
  
  function alternative_data_items_added (in_change_id in change.ch_id%type) return boolean;
  
  function alternative_data_sources_added (in_change_id in change.ch_id%type) return boolean;

  procedure set_alternative_data_items(in_change_id in change.ch_id%type);
  
  procedure skip_dependent_dataitems(in_change_id in change.ch_id%type);
  
  procedure replace_dependent_dataitems(in_change_id in change.ch_id%type);
  
end change_adaptation;
/

create or replace package body change_adaptation as
  -- Change status
  CONST_NEW_CHANGE                types.tp_id%type := 'STT0000001';
  CONST_IN_PROGRESS               types.tp_id%type := 'STT0000002';
  CONST_PROCESSED                 types.tp_id%type := 'STT0000003';
  -- Change adaptation type
  CONST_MANUAL                    types.tp_id%type := 'COP0000001';
  CONST_AUTOMATIC                 types.tp_id%type := 'COP0000002';

  -- Change integration status
  CONST_NOT_ADAPTED               types.tp_id%type := 'CIP0000001';
  CONST_ADAPTED                   types.tp_id%type := 'CIP0000002';

  -- Process author
  CONST_SYSTEM_AUTHOR              author.au_id%type := 1;

  -- Change subtype prefix
  CONST_METADATA_PROPERTY         varchar2(50) := 'METADATA';
  CONST_DATA_ITEM                 varchar2(50) := 'DATA ITEM';
  CONST_DATA_SOURCE               varchar2(50) := 'DATA SOURCE';
  CONST_DATA_HIGHWAY_LVL          varchar2(50) := 'DATA HIGHWAY LEVEL';
  CONST_DATA_SET                  varchar2(50) := 'DATA SET';
  CONST_MAPPING                   varchar2(50) := 'MAPPING';
  CONST_RELATIONSHIP              varchar2(50) := 'RELATIONSHIP';
  CONST_METADATA_VAL_UPDATE       varchar2(50) := 'METADATA VALUE UPDATE';

  -- Special change type
  CONST_DATASET_FORMAT_CH         types.tp_id%type := 'CHT0000032';
  CONST_DATASET_RENAMING          types.tp_id%type := 'CHT0000033';
  CONST_DATAITEM_RENAMING         types.tp_id%type := 'CHT0000034';
  CONST_DATAITEM_TYPE_CH          types.tp_id%type := 'CHT0000035';

  CONST_DATASOURCE_ADDITION       types.tp_id%type := 'CHT0000015';
  CONST_DATAHL_ADDITION           types.tp_id%type := 'CHT0000013';

  -- Scenario step condition type
  CONST_MANUAL_CONDITION          types.tp_id%type := 'CON0000002';
  CONST_AUTOMATIC_CONDITION       types.tp_id%type := 'CON0000001';

  -- Manual condition fulfillment types
  CONST_CONDITION_NOT_FULFILLED   types.tp_id%type := 'MCF0000001';
  CONST_CONDITION_FULFILLED       types.tp_id%type := 'MCF0000002';

  -- Additioal data types
  CONST_DATASET_EXAMPLE           types.tp_id%type := 'CAD0000001';
  CONST_DATASET_STRUCTURE         types.tp_id%type := 'CAD0000002';
  CONST_DATAITEM_STRUCTURE        types.tp_id%type := 'CAD0000003';
  CONST_DATASET_ID                types.tp_id%type := 'CAD0000004';
  CONST_DATAHIGHWAYLEVEL_ID       types.tp_id%type := 'CAD0000005';
  CONST_ALTERNATIVE_DATASOURCES   types.tp_id%type := 'CAD0000006';

  -- Data highway level
  CONST_FIRST_DATA_HIGHWAY_LEVEL  datahighwaylevel.hl_id%type := 2;

  -- Data set formats
  CONST_FORMAT_XML                types.tp_id%type := 'FMT0000011';
  CONST_FORMAT_REL                types.tp_id%type := 'FMT0000031';
  CONST_FORMAT_TXT                types.tp_id%type := 'FMT0000021';
  
  -- Relationship types
  CONT_REL_COMPOSITION            types.tp_id%type := 'RLT0000001';
  
  type t_scenario_step is record (
      operation_type        types.tp_type%type,
      operation_type_id     changeadaptationoperation.cao_operationtype_id%type,
      operation_instruction changeadaptationoperation.cao_operation%type,
      operation_status      types.tp_type%type,
      operation_status_id   changeadaptationprocess.cap_statustype_id%type,
      manual_condition      changeadaptationcondition.cac_condition%type,
      automatic_condition   changeadaptationcondition.cac_condition%type,
      process_id            changeadaptationprocess.cap_id%type,
      cas_parentscenario_id changeadaptationscenario.cas_id%type
  );

---- Returns change type adaption scenario --------------------------------------------------------------------------------------------------------------------------------------------------
  function get_adaptation_scenario(in_change_type in types.tp_id%type) return sys_refcursor is
    v_scenario sys_refcursor;
  begin
    open v_scenario for
      select    cao.cao_operationtype_id operation_type_id,
             cao.cao_operation        operation_instruction, 
             proc.manual_condition    manual_condition,
             proc.automatic_condition automatic_condition
        from (select cas.*,
                     (select listagg(cac.cac_condition, '; ') within group (order by cac.cac_id desc)
                        from ca_conditionmapping cm
                       inner join changeadaptationcondition cac on cac.cac_id = cm.cacm_condition_id
                       where cm.cacm_scenario_id = cas.cas_id
                         and cac.cac_conditiontype_id = CONST_MANUAL_CONDITION) manual_condition,
                     (select listagg(cac.cac_condition, '; ') within group (order by cac.cac_id desc)
                        from ca_conditionmapping cm
                       inner join changeadaptationcondition cac on cac.cac_id = cm.cacm_condition_id
                       where cm.cacm_scenario_id = cas.cas_id
                         and cac.cac_conditiontype_id = CONST_AUTOMATIC_CONDITION) automatic_condition
                from changeadaptationscenario cas
               where cas.cas_changetype_id = in_change_type) proc
       inner join changeadaptationoperation cao on cao.cao_id = proc.cas_operation_id
       inner join types op_t on op_t.tp_id = cao.cao_operationtype_id
       start with proc.cas_parentscenario_id is null
     connect by prior proc.cas_id = proc.cas_parentscenario_id;

   return v_scenario;
  end get_adaptation_scenario;

---- Finds subtype from given pi_parenttype and type description prefix (description without keywords 'addition', 'deletion' at the end)-----------------------------------------------------
  function find_subtype_by_parenttype(in_parenttype in types.tp_parenttype_id%type,
                                      in_type       in varchar2) return types.tp_id%type is
   v_subtype types.tp_id%type default null;
  begin
    begin   
        select t.tp_id
          into v_subtype
          from types t
         where t.tp_parenttype_id = in_parenttype
           and UPPER(t.tp_type) like in_type||'%';
    exception
        when others then
            log_error('Subtype not found! CHANGE_PARENTTYPE_ID=' || in_parenttype || '; CHANGE_TYPE_ID=' || in_type); 
    end;

    return v_subtype;
  end find_subtype_by_parenttype;

---- Defines exact change type (subtype) by change record------------------------------------------------------------------------------------------------------------------------------------
  function define_change_type(in_change in change%rowtype) return types.tp_id%type is
    v_subtype_prefix varchar2(50);
    v_type types.tp_id%type default null;
  begin
    case
      when in_change.ch_datasource_id is not null then
        v_subtype_prefix := CONST_DATA_SOURCE;
      when in_change.ch_metadataproperty_id is not null then
        v_subtype_prefix := CONST_METADATA_PROPERTY;
      when in_change.ch_datahighwaylevel_id is not null then
        v_subtype_prefix := CONST_DATA_HIGHWAY_LVL;
      when in_change.ch_dataset_id is not null then
        if upper(in_change.ch_attrName)='DS_NAME' then
          v_type := CONST_DATASET_RENAMING;
        elsif upper(in_change.ch_attrName)='DS_FORMATTYPE_ID' then
          v_type := CONST_DATASET_FORMAT_CH;
        else
          v_subtype_prefix := CONST_DATA_SET;
        end if;
      when in_change.ch_mapping_id is not null then
        v_subtype_prefix := CONST_MAPPING;
      when in_change.ch_relationship_id is not null then
        v_subtype_prefix := CONST_RELATIONSHIP;
      when in_change.ch_dataitem_id is not null then
        if upper(in_change.ch_attrName)='DI_NAME' then
          v_type := CONST_DATAITEM_RENAMING;
        elsif upper(in_change.ch_attrName)='DI_ITEMTYPE_ID' then
          v_type := CONST_DATAITEM_TYPE_CH;
        else
          v_subtype_prefix := CONST_DATA_ITEM;
        end if;
      /*when in_change.ch_attrname is not null then
        v_subtype_prefix := CONST_METADATA_VAL_UPDATE;
      when in_change.ch_metadataproperty_id is not null then
        v_subtype_prefix := CONST_METADATA_PROPERTY;
      */
      else
        log_error('Could not detect change type! CHANGE_ID: ' || in_change.ch_id);
    end case;

    if v_subtype_prefix is not null then
      v_type := find_subtype_by_parenttype(in_change.ch_changetype_id, v_subtype_prefix);
    end if;

    return v_type;     

  end define_change_type;

---- Insert process record with status 'Not ADAPTED'    ----------------------------------------------------------------------------------------------------------------------------------
  function insert_change_adaptation_proc(in_scenario_id in changeadaptationscenario.cas_id%type,
                                            in_change_id   in change.ch_id%type) return changeadaptationprocess.cap_id%type is    
    v_process changeadaptationprocess%rowtype;
    v_dummy number(10);
  begin

    v_process := null;
    v_process.cap_id := CHANGEADAPTATIONPROCESS_SQ.nextval;
    v_process.cap_scenario_id := in_scenario_id;
    v_process.cap_datetime := sysdate;
    v_process.cap_author_id := CONST_SYSTEM_AUTHOR;
    v_process.cap_statustype_id := CONST_NOT_ADAPTED;
    v_process.cap_change_id := in_change_id;

    insert into changeadaptationprocess 
         values v_process;

    select count(1) into v_dummy from changeadaptationprocess;
    return v_process.cap_id;

  end insert_change_adaptation_proc;

---- Set manual condition as fulfilled ------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure set_manual_condition_fulfilled(in_change_id in change.ch_id%type,
                                           in_condition_id in changeadaptationcondition.cac_id%type) is

  begin
    update ca_manualconditionfulfillment mcf
       set camcf_fulfillmentstatus_id = 'MCF0000002'
     where mcf.camcf_change_id = in_change_id
       and mcf.camcf_condition_id = in_condition_id;
  end set_manual_condition_fulfilled;

---- Insert manual condition fulfillment records --------------------------------------------------------------------------------------------------------------------------------------------
  procedure insert_manual_condition_fulf(in_process_id  in changeadaptationprocess.cap_id%type,
                                                in_scenario_id in changeadaptationscenario.cas_id%type,
                                                in_change_id   in change.ch_id%type)is
    v_manualconditionfulfillment ca_manualconditionfulfillment%rowtype;
    v_conditions_inserted        number;
  begin
    for condition in (select distinct c.cacm_condition_id as condition_id
                        from ca_conditionmapping c
                       inner join changeadaptationcondition cac on cac.cac_id = c.cacm_condition_id
                       where c.cacm_scenario_id = in_scenario_id
                         and cac.cac_conditiontype_id = CONST_MANUAL_CONDITION) loop

      select count(1) as cond_count
        into v_conditions_inserted
        from ca_manualconditionfulfillment mcf
       where mcf.camcf_change_id = in_change_id
         and mcf.camcf_condition_id = condition.condition_id;

      if v_conditions_inserted = 0 then
          v_manualconditionfulfillment := null;
          v_manualconditionfulfillment.camcf_id := ca_manualconditionfulfill_SQ.nextval;
          v_manualconditionfulfillment.camcf_condition_id := condition.condition_id;
          v_manualconditionfulfillment.camcf_change_id := in_change_id;
          v_manualconditionfulfillment.camcf_fulfillmentstatus_id := CONST_CONDITION_NOT_FULFILLED;

          insert into ca_manualconditionfulfillment values v_manualconditionfulfillment;
      end if;
    end loop;
  end insert_manual_condition_fulf;

---- Update change record to 'In progress'---------------------------------------------------------------------------------------------------------------------------------------------------
  procedure update_change_in_progress(in_change_id change.ch_id%type)is
  begin

    update change c
       set c.ch_statustype_id = CONST_IN_PROGRESS
     where c.ch_id = in_change_id;

    if SQL%rowcount = 0 then 
      log_error('Could not find change record! CHANGE_ID=' || in_change_id);
    end if;

  end update_change_in_progress;

---- Creates adaptation processes for all not adapted changes--------------------------------------------------------------------------------------------------------------------------------
  procedure create_change_adaptation_proc is    
    v_change_type     types.tp_id%type;
    v_process_created boolean;
    v_process_id      changeadaptationprocess.cap_id%type;
  begin
    -- loops through all new changes
    for new_change in (select *
                         from change c
                        where c.ch_statustype_id = CONST_NEW_CHANGE
                        order by c.ch_id
                    ) loop
      v_process_created := false;
      v_change_type := define_change_type(new_change);
      dbms_output.put_line(v_change_type); 
      if v_change_type is not null then
          --  for each new change finds all possible integration scenario steps              
          for scenario_step in (select cas.cas_id
                                  from changeadaptationscenario cas
                                 where cas.cas_changetype_id = v_change_type
                                 start with cas.cas_parentscenario_id is null
                               connect by prior cas.cas_id = cas_parentscenario_id
                                 order by cas.cas_operation_id) loop

            v_process_id := insert_change_adaptation_proc(scenario_step.cas_id, new_change.ch_id);
            insert_manual_condition_fulf(v_process_id, scenario_step.cas_id, new_change.ch_id);
            v_process_created := true;
            dbms_output.put_line('Process step created!');            
          end loop;

          if v_process_created then
            update_change_in_progress(new_change.ch_id);          
            dbms_output.put_line('Change status updated!');
          end if;

          dbms_output.put_line('   ');
      end if;
    end loop;
  end create_change_adaptation_proc;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure add_dataset_example(in_change_id in change.ch_id%type,
                                in_data_type in types.tp_id%type,
                                in_data      in changeadaptationadditionaldata.caad_data%type) is

  begin
    insert into changeadaptationadditionaldata values (CHANGEADAPTADDITIONALDATA_SQ.nextval, in_data_type, in_change_id, in_data);
  end add_dataset_example;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataset_example_added(in_change_id in change.ch_id%type) return boolean is
    v_added_count number;
    v_is_added boolean default false;
  begin
    select count(1)
      into v_added_count
      from changeadaptationadditionaldata caad
     where caad.caad_change_id = in_change_id;

    if v_added_count > 0 then
      v_is_added := true;    
    end if;

      dbms_output.put_line('in function dataset example added');

    return v_is_added;    
  end dataset_example_added;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataitem_from (in_change_id in change.ch_id%type) return varchar2 is
  
    v_type varchar2(20);
  begin
    select decode(ds_datasource_id, null, decode(ds_datahighwaylevel_id, null, null, CONST_DATA_HIGHWAY_LVL), CONST_DATA_SOURCE)
      into v_type
      from change ch 
    join dataitem di on di_id=ch_dataitem_id
    join dataset ds on ds_id=di_dataset_id
    where ch_id=in_change_id;

    return v_type;    
  end dataitem_from;
  
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataitem_from_datasource (in_change_id in change.ch_id%type) return boolean is
  
    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    v_type := dataitem_from(in_change_id);
    if v_type = CONST_DATA_SOURCE then
      v_fulfilled := true;    
    end if;

    return v_fulfilled;        
  end dataitem_from_datasource;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataitem_from_dhlevel (in_change_id in change.ch_id%type) return boolean is

    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    v_type := dataitem_from(in_change_id);
    if v_type = CONST_DATA_HIGHWAY_LVL then
      v_fulfilled := true;    
    end if;

    return v_fulfilled;      
  end dataitem_from_dhlevel;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function alternative_data_sources_added (in_change_id in change.ch_id%type) return boolean is
  
    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    

    return v_fulfilled;    
  end alternative_data_sources_added;  
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function alternative_data_items_added (in_change_id in change.ch_id%type) return boolean is
  
    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    

    return v_fulfilled;    
  end alternative_data_items_added;
  
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataitem_added_to_datasource (in_change_id in change.ch_id%type) return boolean is
  
    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    v_type := dataitem_from(in_change_id);
    if v_type = CONST_DATA_SOURCE then
      v_fulfilled := true;    
    end if;

    return v_fulfilled;    
  end dataitem_added_to_datasource;
  
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function dataitem_added_to_dhlevel (in_change_id in change.ch_id%type) return boolean is

    v_type varchar2(20);
    v_fulfilled boolean default false;
  begin
    v_type := dataitem_from(in_change_id);
    if v_type = CONST_DATA_HIGHWAY_LVL then
      v_fulfilled := true;    
    end if;

    return v_fulfilled;     
  end dataitem_added_to_dhlevel;
  
---- Returns cahnge scenario with all conditions----------------------------------------------------------------------------------------------------------------------------------------------------
  function get_change_adaptation_scenario(in_change_id in change.ch_id%type) return sys_refcursor is
    v_scenario sys_refcursor;
  begin
    open v_scenario for
      select op_t.tp_type             operation_type, 
             cao.cao_operationtype_id operation_type_id,
             cao.cao_operation        operation_instruction, 
             pr_st.tp_type            operation_status, 
             proc.cap_statustype_id   operation_status_id, 
             proc.manual_condition    manual_condition,
             proc.automatic_condition automatic_condition,
             proc.cap_id              process_id,
             proc.cas_parentscenario_id
        from (select cap.cap_statustype_id, 
                     cap.cap_id,
                     cas.*,
                     (select listagg(cac.cac_condition, '; ') within group (order by cac.cac_id desc)
                        from ca_conditionmapping cm
                       inner join changeadaptationcondition cac on cac.cac_id = cm.cacm_condition_id
                       where cm.cacm_scenario_id = cas.cas_id
                         and cac.cac_conditiontype_id = CONST_MANUAL_CONDITION) manual_condition,
                     (select listagg(cac.cac_condition, '; ') within group (order by cac.cac_id desc)
                        from ca_conditionmapping cm
                       inner join changeadaptationcondition cac on cac.cac_id = cm.cacm_condition_id
                       where cm.cacm_scenario_id = cas.cas_id
                         and cac.cac_conditiontype_id = CONST_AUTOMATIC_CONDITION) automatic_condition
                from changeadaptationprocess cap
               inner join changeadaptationscenario cas on cas.cas_id = cap.cap_scenario_id
               where cap.cap_change_id = in_change_id) proc
       inner join changeadaptationoperation cao on cao.cao_id = proc.cas_operation_id
       inner join types op_t on op_t.tp_id = cao.cao_operationtype_id
       inner join types pr_st on pr_st.tp_id = proc.cap_statustype_id
       start with proc.cas_parentscenario_id is null
     connect by prior proc.cas_id = proc.cas_parentscenario_id;

   return v_scenario;
  exception
    when no_data_found then
      log_error('Change not found! CHANGE_ID=' || in_change_id);
  end get_change_adaptation_scenario;

---- Counts how many manual conditions of the process are not fulfilled yet -----------------------------------------------------------------------------------------------------------------
  function manual_not_fulfill_cond_count(in_process_id in changeadaptationprocess.cap_id%type) return number as
    v_not_fulfilled_count         number(10);
  begin
    select count(2)
      into v_not_fulfilled_count
      from ca_manualconditionfulfillment mcf
     where (mcf.camcf_condition_id, mcf.camcf_change_id) in (select cac.cac_id, cap.cap_change_id
                                        from changeadaptationprocess cap
                                       inner join ca_conditionmapping cacm on cacm.cacm_scenario_id = cap.cap_scenario_id
                                       inner join changeadaptationcondition cac on cac.cac_id = cacm.cacm_condition_id
                                       where cap.cap_id = in_process_id
                                         and cac.cac_conditiontype_id = CONST_MANUAL_CONDITION)
       and mcf.camcf_fulfillmentstatus_id = CONST_CONDITION_NOT_FULFILLED;

    return v_not_fulfilled_count;
  end manual_not_fulfill_cond_count;

---- Check all conditions--------------------------------------------------------------------------------------------------------------------------------------------------------------------
  function conditions_fulfilled(in_change_id            in change.ch_id%type,
                                in_automatic_conditions in changeadaptationcondition.cac_condition%type,
                                in_process_id           in changeadaptationprocess.cap_id%type) return boolean is
    v_coditions_fulfilled         boolean default true;
    v_condition_met               boolean default true;
  begin
    if in_automatic_conditions is not null then
        for condition in(with conditions as
                           (select in_automatic_conditions cond from dual)
                       select trim(regexp_substr(cond, '[^;]+', 1, level)) function_name
                         from conditions
                      connect by trim(regexp_substr(cond, '[^;]+', 1, level)) is not null) loop

          execute immediate
            'begin
               :result :=' || condition.function_name ||'(:change_id);
             end;'
          using out v_condition_met, in in_change_id; 

          if not v_condition_met then
            v_coditions_fulfilled := false;
            exit;
          end if;

        end loop;
    end if;

    if v_coditions_fulfilled and manual_not_fulfill_cond_count(in_process_id) > 0 then
      v_coditions_fulfilled := false;
    end if;

    return v_coditions_fulfilled;

  end conditions_fulfilled;

---- Execute specific change adaptation procedure -------------------------------------------------------------------------------------------------------------------------------------------
  procedure execute_adaptation_procedure(in_change_id      in change.ch_id%type,
                                         in_procedure_name in changeadaptationoperation.cao_operation%type) is                                     
  begin
    execute immediate
      'begin
         ' || in_procedure_name ||'(:change_id);
       end;'
    using in_change_id;
  end execute_adaptation_procedure;

---- Set process adapted --------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure set_process_adapted(in_process_id in changeadaptationprocess.cap_id%type) is
  begin
    update changeadaptationprocess cap
       set cap.cap_statustype_id = CONST_ADAPTED
     where cap.cap_id = in_process_id;
  end set_process_adapted;

---- Set change status to processed if all process steps executed ---------------------------------------------------------------------------------------------------------------------------------------
  procedure set_to_processed_if_all_exec(in_change_id in change.ch_id%type) is    
    v_count number(10);
    v_adapted number(10);
    v_parent changeadaptationscenario.cas_id%type;
  begin
    /*select sum(decode(cap_statustype_id,CONST_ADAPTED,1,0)), count(*) into v_adapted, v_count 
    from changeadaptationprocess where cap_change_id=in_change_id;
    
    if v_count>0 and v_count=v_adapted then
      update change set ch_statustype_id=CONST_PROCESSED where ch_id=in_change_id;
    end if;*/
    begin
      SELECT cas_parentscenario_id into v_parent
        FROM (
        SELECT CONNECT_BY_ROOT cas.cas_id as cas_parentscenario_id, cap_statustype_id
            FROM (
            select *
            from changeadaptationprocess proc join changeadaptationscenario cas on cas.cas_id=proc.cap_scenario_id
            where cap_change_id=in_change_id) cas    
        START WITH cas.cas_parentscenario_id is null
        CONNECT BY PRIOR cas.cas_id = cas.cas_parentscenario_id  )
      GROUP BY cas_parentscenario_id
      HAVING sum(decode(cap_statustype_id,'CIP0000002',1,0)) = count(*) and count(*)>0;    
      
      update change set ch_statustype_id=CONST_PROCESSED where ch_id=in_change_id;
    exception 
      when no_data_found then null;
    end;
  end set_to_processed_if_all_exec;
  
---- Tries to execute adaptation scenario for specific change (only consecutive, not already adapted and automatic change scenario operations can be executed)
  procedure run_change_adaptation_scenario(in_change_id in change.ch_id%type) is
    v_change_scenario sys_refcursor;   
    v_scenario_step t_scenario_step;
    v_next_first boolean := false;
  begin
    v_change_scenario := get_change_adaptation_scenario(in_change_id);
    loop
      fetch v_change_scenario into v_scenario_step;
      exit when v_change_scenario%notfound;

      -- processes only not ADAPTED automatic changes
      if v_next_first = false or v_next_first = true and v_scenario_step.cas_parentscenario_id is null then      
        if v_scenario_step.operation_status_id = CONST_NOT_ADAPTED then

            --check conditions if they exist
            if v_scenario_step.operation_type_id = CONST_AUTOMATIC and conditions_fulfilled(in_change_id, v_scenario_step.automatic_condition, v_scenario_step.process_id) then
              dbms_output.put_line(v_scenario_step.operation_instruction);
              execute_adaptation_procedure(in_change_id, v_scenario_step.operation_instruction);
              set_process_adapted(v_scenario_step.process_id);
              v_next_first := false;
            else
              v_next_first := true;
            end if;
        else
          v_next_first := false;
        end if;
      end if;

    end loop;
    set_to_processed_if_all_exec(in_change_id);    
  end run_change_adaptation_scenario;  

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure process_change_adaptations is
  begin
    for ch in (select c.ch_id
                     from change c
                    where c.ch_statustype_id = CONST_IN_PROGRESS) loop

      run_change_adaptation_scenario(ch.ch_id);

    end loop;
  end process_change_adaptations;

---- Processes all uningtegrated changes-----------------------------------------------------------------------------------------------------------------------------------------------------
  procedure adapt_changes is    
    v_dummy number(10);
  begin
    create_change_adaptation_proc;

    process_change_adaptations;

  end adapt_changes;

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure del_dependent_mappings (in_di_id dataitem.di_id%type) is
    v_count number(10);
  begin
    for v_map in (select * 
        from mappingorigin join mapping on mp_id = ms_mapping_id
        where ms_origin_dataitem_id=in_di_id) loop
      
      -- check if target data item may be obtained some other way
      select count(*) into v_count
        from mapping 
        where mp_target_dataitem_id = v_map.mp_target_dataitem_id
        and mp_deleted is null
        and not exists (select 1 from mappingorigin where ms_mapping_id=mp_id and ms_origin_dataitem_id=in_di_id);
      if v_count = 0 then
      -- delete mappings for dependent data items
        del_dependent_mappings(v_map.mp_target_dataitem_id);
      end if;
      
      -- delete mapping
      update mapping set mp_deleted = sysdate where mp_id=v_map.mp_id;
    end loop;
  end;

---- Rename data highway level -------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure rename_dhighlevel(in_change_id in change.ch_id%type) is
    v_name change.ch_newattrvalue%type;
    v_hl_id change.ch_datahighwaylevel_id%type;
  begin
    select ch_newattrvalue, ch_datahighwaylevel_id into v_name, v_hl_id from change where ch_id=in_change_id;
    update datahighwaylevel set hl_name=v_name where hl_id=v_hl_id;
  end rename_dhighlevel;

---- Get dataset structure ------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure get_dataset_structure(in_change_id in change.ch_id%type) is

        v_name varchar2(100);
        v_so_id number(10);
        v_hl_id number(10);
        v_ds_desc varchar2(4000);
        v_velocity_id types.tp_id%type;
        v_formattype_id types.tp_id%type;
        v_freq varchar2(100);
        v_path varchar2(1000);
  begin
    -- zgrd.xmln procedure must be executed for xml documents -- to do: add automatic execution
    for v_data in (select * from changeAdaptationAdditionalData where caad_change_id=in_change_id) loop
      
      v_formattype_id := helpers.get_value_from_str(v_data.caad_data, 'Format');
      v_name := helpers.get_value_from_str(v_data.caad_data, 'Data source name');
      v_ds_desc := helpers.get_value_from_str(v_data.caad_data, 'Data source description', true);
      v_velocity_id := helpers.get_value_from_str(v_data.caad_data, 'Velocity');
      v_freq := helpers.get_value_from_str(v_data.caad_data, 'Frequency', true);
      v_path := helpers.get_value_from_str(v_data.caad_data, 'Path', true);
      
      select ch_datasource_id, ch_datahighwaylevel_id into v_so_id, v_hl_id from change where ch_id=in_change_id;
      
      if v_formattype_id=CONST_FORMAT_XML then
        POSTDOC_METADATA.gather_xml_metadata(v_name, null, v_so_id, v_hl_id, v_ds_desc, v_velocity_id, null, v_formattype_id, 
          v_freq, null, false);
      
      elsif v_formattype_id=CONST_FORMAT_REL then
        POSTDOC_METADATA.gather_table_metadata(v_name, null, v_so_id, v_hl_id, v_ds_desc, v_velocity_id, null, v_formattype_id, 
          v_freq, null, false);
      
      elsif v_formattype_id=CONST_FORMAT_TXT then
        POSTDOC_METADATA.create_unstructured_dataset(v_name, v_path, null, v_so_id, v_hl_id, v_ds_desc, v_velocity_id, null, 
          v_formattype_id, v_freq, null, false);
      end if;
      
    end loop;
    
  end get_dataset_structure;

---- Get dataitem structure ------------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure get_dataitem_structure(in_change_id in change.ch_id%type) is

  begin
   null; -- to be implemented
  end get_dataitem_structure;

---- Set alternative data sources ------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure set_alternative_data_sources(in_change_id in change.ch_id%type) is

  begin
   null; -- to be implemented
  end set_alternative_data_sources;
  
---- Set alternative data items ------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure set_alternative_data_items(in_change_id in change.ch_id%type) is

  begin
   null; -- to be implemented
  end set_alternative_data_items;
  
---- Skip dependent data items ------------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure skip_dependent_dataitems(in_change_id in change.ch_id%type) is
    v_di_id dataitem.di_id%type;
  begin
    select ch_dataitem_id into v_di_id
      from change
      where ch_id = in_change_id;
    -- set mappings for dependent data items as deleted
    del_dependent_mappings (v_di_id);
   
    -- get child data items
    for v_child in (select re_child_dataitem_id as child_id
        from relationship 
        join relationshipelement on re_relationship_id=rl_id
        where rl_parent_dataitem_id = v_di_id and rl_relationshiptype_id=CONT_REL_COMPOSITION) loop
        
      del_dependent_mappings (v_child.child_id);
    end loop;
  end skip_dependent_dataitems;
  
---- Replace dependent data items ---------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure replace_dependent_dataitems(in_change_id in change.ch_id%type) is

  begin
  
   null; -- to be implemented
  end replace_dependent_dataitems;
  
---- Get dataset id
  function get_additional_dataset_id(in_change_id in change.ch_id%type) return dataset.ds_id%type is
    v_dataset_id dataset.ds_id%type;
  begin
    select to_number(caad_data)
      into v_dataset_id
      from changeadaptationadditionaldata
     where caad_change_id = in_change_id
       and caad_data_type_id = CONST_DATASET_ID;

    return v_dataset_id;
  end get_additional_dataset_id;

---- Add dataset to data highway level ------------------------------------------------------------------------------------------------------------------------------------------------------
  procedure add_dataset_to_dhighwaylevel(in_change_id in change.ch_id%type) is
    v_datahighwaylevel_id datahighwaylevel.hl_id%type;
    v_dataset_id dataset.ds_id%type;
  begin
    v_dataset_id := get_additional_dataset_id(in_change_id);
     -- gets data highway level id from change record
    select ch_datahighwaylevel_id
      into v_datahighwaylevel_id
      from change
     where ch_id = in_change_id;
     --sets data highway level of the data set   
    update dataset
       set ds_datahighwaylevel_id = v_datahighwaylevel_id
     where ds_id = v_dataset_id;
  end add_dataset_to_dhighwaylevel;

---- Add dataset to the first data highway level
  procedure add_dataset_to_1st_dhighlevel(in_change_id in change.ch_id%type) is
    v_hl_id datahighwaylevel.hl_id%type;
    v_ds_id dataset.ds_id%type;
  begin
    -- get id of data highwaylevel Raw data level
    select hl_id into v_hl_id from datahighwaylevel where upper(hl_name)=upper('Raw data level');
    
    -- get datasets from the changed data source
    for v_dataset in (select ds.ds_id   
      from changeadaptationadditionaldata caad 
      join change ch on ch.ch_id=caad.caad_change_id
      join dataset ds on ds.ds_datasource_id=ch.ch_datasource_id and ds.ds_name=helpers.get_value_from_str(caad_data, 'Data source name')
      where caad.caad_change_id = in_change_id) loop
      
      v_ds_id:=postdoc_metadata.copy_dataset_to_dhighlevel(v_dataset.ds_id, v_hl_id);
    end loop; 

  end add_dataset_to_1st_dhighlevel;

-- Add data item to the 1st level of the data highway
  procedure add_dataitem_to_1st_dhighlevel(in_change_id in change.ch_id%type) is
    v_ds_id dataset.ds_id%type;
    v_di_id dataitem.di_id%type;
    v_new_di_id dataitem.di_id%type;
    v_parent_di_id dataitem.di_id%type;
    v_rltype types.tp_id%type;
    v_rl_id relationship.rl_id%type;
  begin
    -- get data set at the 1st level of the data highway
    select ds2.ds_id 
      into v_ds_id
      from change 
      join dataitem on di_id=ch_dataitem_id
      join dataset ds on ds.ds_id=di_dataset_id
      join dataset ds2 on upper(ds2.ds_name) = upper(ds.ds_name)
      join datahighwaylevel on hl_id=ds2.ds_datahighwaylevel_id
      where ch_id=in_change_id and upper(hl_name)=upper('Raw data level');
    
    -- get changed dataitem
    select ch_dataitem_id 
      into v_di_id 
      from change 
      where ch_id = in_change_id;
          
    -- copy data item metadata
    v_new_di_id:=postdoc_metadata.copy_dataitem_to_dataset(v_di_id, v_ds_id);
    
    -- if it is child of other data item, create the necessary relationship
    for v_rel in
      (select mp_target_dataitem_id, rl_relationshiptype_id 
        into v_parent_di_id, v_rltype
        from relationshipelement 
        join relationship on rl_id=re_relationship_id
        join mappingorigin on ms_origin_dataitem_id=rl_parent_dataitem_id
        join mapping on mp_id=ms_mapping_id
        where re_child_dataitem_id=v_di_id) loop
        
      select relationship_rl_id_seq.nextval into v_rl_id from dual;
      insert into relationship (rl_id, rl_parent_dataitem_id, rl_relationshiptype_id,  rl_created)
        values (v_rl_id, v_rel.mp_target_dataitem_id, v_rel.rl_relationshiptype_id, sysdate);
      
      insert into relationshipelement (re_child_dataitem_id, re_relationship_id)
        values (v_new_di_id, v_rl_id);
    end loop;

  end add_dataitem_to_1st_dhighlevel;

end change_adaptation;
/