CREATE TABLE changeadaptationprocess (
    cap_id                    NUMBER(10) NOT NULL,
    cap_scenario_id           number(10) not null,
    cap_datetime              timestamp not null,
    cap_author_id             number(10,0) not null,
    cap_statustype_id         VARCHAR2(10 BYTE) not null,
    cap_change_id             number(10) not null
);

ALTER TABLE changeadaptationprocess ADD CONSTRAINT process_pk PRIMARY KEY ( cap_id );

ALTER TABLE changeadaptationprocess
    ADD CONSTRAINT cap_scenario_fk FOREIGN KEY ( cap_scenario_id )
        REFERENCES changeadaptationscenario ( cas_id );
        
ALTER TABLE changeadaptationprocess
    ADD CONSTRAINT cap_author_fk FOREIGN KEY ( cap_author_id )
        REFERENCES author ( AU_ID );
        
ALTER TABLE changeadaptationprocess
    ADD CONSTRAINT cap_statustype_fk FOREIGN KEY ( cap_statustype_id )
        REFERENCES types ( tp_id );
        
ALTER TABLE changeadaptationprocess
    ADD CONSTRAINT cap_change_fk FOREIGN KEY ( cap_change_id )
        REFERENCES change ( ch_id );

CREATE SEQUENCE changeadaptationprocess_SQ INCREMENT BY 1 START WITH 1 MAXVALUE 999999 MINVALUE 1;
