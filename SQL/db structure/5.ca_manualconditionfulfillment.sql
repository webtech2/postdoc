create table ca_manualconditionfulfillment (
  camcf_id                   number(10) not null,
  camcf_condition_id       number(10) not null,
  camcf_change_id           number(10) not null,
  camcf_fulfillmentstatus_id VARCHAR2(10 BYTE) not null
);

ALTER TABLE ca_manualconditionfulfillment ADD CONSTRAINT ca_manualconditionfulfillment_pk PRIMARY KEY ( camcf_id );

ALTER TABLE ca_manualconditionfulfillment
    ADD CONSTRAINT camcf_condition_fk FOREIGN KEY ( camcf_condition_id )
        REFERENCES changeadaptationcondition ( cac_id );
        
ALTER TABLE ca_manualconditionfulfillment
    ADD CONSTRAINT camcf_change_fk FOREIGN KEY ( camcf_change_id )
        REFERENCES change ( ch_id );
        
ALTER TABLE ca_manualconditionfulfillment
    ADD CONSTRAINT camcf_fulfillmentstatus_fk FOREIGN KEY ( camcf_fulfillmentstatus_id )
        REFERENCES types ( tp_id );
        
CREATE SEQUENCE ca_manualconditionfulfill_SQ INCREMENT BY 1 START WITH 1 MAXVALUE 999999 MINVALUE 1;