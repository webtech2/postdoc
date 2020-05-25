CREATE TABLE changeadaptationcondition (
    cac_id                    NUMBER(10) NOT NULL,
    cac_condition             VARCHAR2(4000) not null
);


ALTER TABLE changeadaptationcondition ADD CONSTRAINT condition_pk PRIMARY KEY ( cac_id );

alter table changeadaptationcondition add cac_conditiontype_id VARCHAR2(10 BYTE) not null;

ALTER TABLE changeadaptationcondition
    ADD CONSTRAINT cac_conditiontype_fk FOREIGN KEY ( cac_conditiontype_id )
        REFERENCES types ( tp_id );
