create or replace procedure log_error (in_error in VARCHAR2) as 
begin
  insert into error_log values (sysdate, in_error);
end log_error;