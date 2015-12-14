-- @todo if events are appended on difference machines -- sequence problem
-- sequence reaches int unsigned
-- version * event_stream_version possible entries per aggregate
-- could use auto_increment for version per aggregate_id
-- data limited to 16MB
-- @todo use postgresql to also save json entities in the same transaction? or eventual consistent? - https://vaughnvernon.co/?p=942
-- 32 char keys
CREATE TABLE events (aggregate_id varchar(32) not null, data MEDIUMBLOB not null, version int unsigned not null, event_stream_version int unsigned not null, sequence int unsigned not null auto_increment, unique key(aggregate_id, version, event_stream_version), key(aggregate_id, version), key(sequence));