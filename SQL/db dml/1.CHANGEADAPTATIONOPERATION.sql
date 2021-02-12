REM INSERTING into CHANGEADAPTATIONOPERATION
SET DEFINE OFF;
Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (1,'COP0000001','Please make a manual decision about this change.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (2,'COP0000001','Describe the new structure in metadata.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (3,'COP0000001','Implement the new structure.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (4,'COP0000001','Define ETL processes in mapping metadata.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (5,'COP0000001','Add dataset examples.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (6,'COP0000002','change_adaptation.get_dataset_structure');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (7,'COP0000002','change_adaptation.add_dataset_to_dhighwaylevel');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (8,'COP0000001','Define data highway level');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (9,'COP0000002','change_adaptation.add_dataset_to_1st_dhighlevel');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (10,'COP0000001','Define other data highway levels');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (11,'COP0000001','Define data set');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (12,'COP0000001','Add data item examples');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (13,'COP0000002','change_adaptation.get_dataitem_structure');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (14,'COP0000002','change_adaptation.add_dataitem_to_dataset');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (15,'COP0000001','Change ETL processes in mapping metadata');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (16,'COP0000001','Define alternative data sources');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (17,'COP0000002','change_adaptation.set_alternative_data_sources');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (18,'COP0000002','change_adaptation.rename_dhighlevel');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (19,'COP0000002','change_adaptation.add_dataitem_to_1st_dhighlevel');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (20,'COP0000001','Define mapping for the new data item and metadata properties.');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (21,'COP0000001','Add new data source');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (22,'COP0000001','Define alternative data items');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (23,'COP0000002','change_adaptation.set_alternative_data_items');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (24,'COP0000002','change_adaptation.skip_dependent_dataitems');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (25,'COP0000002','change_adaptation.replace_dependent_dataitems');

Insert into CHANGEADAPTATIONOPERATION (CAO_ID,CAO_OPERATIONTYPE_ID,CAO_OPERATION) 
values (26,'COP0000001','Adapt dependent ETL procedures to utilize the new value of the property');
