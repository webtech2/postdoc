CREATE TABLE changeadaptationscenario (
    cas_id                    NUMBER(10) NOT NULL,
    cas_operation_id          number(10) not null,
    cas_parentscenario_id     number(10),
    cas_changetype_id         VARCHAR2(10 BYTE) not null
);

ALTER TABLE changeadaptationscenario ADD CONSTRAINT scenario_pk PRIMARY KEY ( cas_id );
        
ALTER TABLE changeadaptationscenario
    ADD CONSTRAINT cas_operation_fk FOREIGN KEY ( cas_operation_id )
        REFERENCES changeadaptationoperation ( cao_id );
        
ALTER TABLE changeadaptationscenario
    ADD CONSTRAINT cas_parentscenario_fk FOREIGN KEY ( cas_parentscenario_id )
        REFERENCES changeadaptationscenario ( cas_id );
        
ALTER TABLE changeadaptationscenario
    ADD CONSTRAINT cas_changetype_fk FOREIGN KEY ( cas_changetype_id )
        REFERENCES types ( tp_id );

insert into changeadaptationscenario values(1, 1, null, 'CHT0000013');
insert into changeadaptationscenario values(2, 2, 1, 'CHT0000013');
insert into changeadaptationscenario values(3, 3, 2, 'CHT0000013');
insert into changeadaptationscenario values(5, 5, 3, 'CHT0000013');
insert into changeadaptationscenario values(4, 4, 5, 'CHT0000013');
