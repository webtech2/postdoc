CREATE TABLE changeadaptationoperation (
    cao_id                    NUMBER(10) NOT NULL,
    cao_operationtype_id      VARCHAR2(10 BYTE) not null,
    cao_operation             VARCHAR2(4000) not null
);

ALTER TABLE changeadaptationoperation ADD CONSTRAINT integration_pk PRIMARY KEY ( cao_id );

ALTER TABLE changeadaptationoperation
    ADD CONSTRAINT cao_operationtype_fk FOREIGN KEY ( cao_operationtype_id )
        REFERENCES types ( tp_id );
        


insert into changeadaptationoperation values (
    1,
    'COP0000001',
    'Define the new structure metadata.'
);

insert into changeadaptationoperation values (
    2,
    'COP0000001',
    'Implement the new structure.'
);

insert into changeadaptationoperation values (
    3,
    'COP0000001',
    'Implement ETL processes in mapping metadata.'
);
insert into changeadaptationoperation values (
    4,
    'COP0000002',
    'Get the new structure metadata.'
);
insert into changeadaptationoperation values (
    5,
    'COP0000001',
    'Add dataset examples.'
);