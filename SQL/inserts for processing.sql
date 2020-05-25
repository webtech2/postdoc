INSERT INTO types VALUES (
    'COP0000000',
    'Change integration operation type',
    NULL
);

INSERT INTO types VALUES (
    'COP0000001',
    'Manual',
    'COP0000000'
);

INSERT INTO types VALUES (
    'COP0000002',
    'Automatic',
    'COP0000000'
);


insert into types values ('CHT0000011', 'Metadata property addition', 'CHT0000001');
insert into types values ('CHT0000012', 'Data item addition', 'CHT0000001');
insert into types values ('CHT0000013', 'Data highway level addition', 'CHT0000001');
insert into types values ('CHT0000014', 'Data set addition', 'CHT0000001');
insert into types values ('CHT0000015', 'Data source addition', 'CHT0000001');

insert into types values ('CHT0000021', 'Metadata property deletion', 'CHT0000002');
insert into types values ('CHT0000022', 'Data item deletion', 'CHT0000002');
insert into types values ('CHT0000023', 'Data highway level deletion', 'CHT0000002');
insert into types values ('CHT0000024', 'Data set deletion', 'CHT0000002');

insert into types values ('CHT0000031', 'Metadata value update', 'CHT0000003');

INSERT INTO types VALUES (
    'CAP0000000',
    'Change adaptation operation status type',
    NULL
);

INSERT INTO types VALUES (
    'CAP0000001',
    'Not adapted',
    'CAP0000000'
);

INSERT INTO types VALUES (
    'CAP0000002',
    'Adapted',
    'CAP0000000'
);


INSERT INTO types VALUES (
    'CAD0000000',
    'Change adaptation additional data type',
    NULL
);

INSERT INTO types VALUES (
    'CAD0000001',
    'Dataset example',
    'CAD0000000'
);

INSERT INTO types VALUES (
    'CAD0000002',
    'Data set structure',
    'CAD0000000'
);
INSERT INTO types VALUES (
    'CAD0000003',
    'Data item structure',
    'CAD0000000'
);
INSERT INTO types VALUES (
    'CAD0000004',
    'Data set ID',
    'CAD0000000'
);
INSERT INTO types VALUES (
    'CAD0000005',
    'Data highway level ID',
    'CAD0000000'
);
INSERT INTO types VALUES (
    'CAD0000006',
    'Alternative data sources',
    'CAD0000000'
);




INSERT INTO types VALUES (
    'CON0000000',
    'Condition type',
    NULL
);

INSERT INTO types VALUES (
    'CON0000001',
    'Automatic condition',
    'CAD0000000'
);

INSERT INTO types VALUES (
    'CON0000002',
    'Manual condition',
    'CAD0000000'
);



INSERT INTO types VALUES (
    'MCF0000000',
    'Manual contition fulfillment type',
    NULL
);

INSERT INTO types VALUES (
    'MCF0000001',
    'Manual condition not fulfilled',
    'MCF0000000'
);

INSERT INTO types VALUES (
    'MCF0000002',
    'Manual condition fulfilled',
    'MCF0000000'
);



