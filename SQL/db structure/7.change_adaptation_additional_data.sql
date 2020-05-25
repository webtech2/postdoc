CREATE TABLE changeadaptationadditionaldata (
    caad_id                   NUMBER(10) NOT NULL,
    caad_data_type_id         VARCHAR2(10 BYTE) not null,
    caad_change_id            NUMBER(10) not null,
    caad_data                 clob not null
);

ALTER TABLE changeadaptationadditionaldata ADD CONSTRAINT additional_data_pk PRIMARY KEY ( caad_id );

ALTER TABLE changeadaptationadditionaldata
    ADD CONSTRAINT caad_data_type_fk FOREIGN KEY ( caad_data_type_id )
        REFERENCES types ( tp_id );
        
ALTER TABLE changeadaptationadditionaldata
    ADD CONSTRAINT caad_change_fk FOREIGN KEY ( caad_change_id )
        REFERENCES change ( ch_id );
        
CREATE SEQUENCE CHANGEADAPTADDITIONALDATA_SQ INCREMENT BY 1 START WITH 1 MAXVALUE 999999999 MINVALUE 1;
