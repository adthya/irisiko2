
COPY fact_aws FROM 'D:\test\Fact_AWS.csv' using delimiters ';' csv header




CREATE TABLE "fact_aws"
(
  "date" date NOT NULL, -- 
  "time" time without time zone NOT NULL,
  "statkey" integer NOT NULL, -- 
  "temperature" numeric,
  "windir" integer,
  "windspeed" numeric
)
WITH (
  OIDS=FALSE
);


CREATE TABLE dim_stat
(
  statkey integer NOT NULL,
  stat_id character varying(25) NOT NULL,
  stat_name character varying(100) NOT NULL,
  region character varying(100),
  lat numeric(7,4),
  lon numeric(7,4),
  CONSTRAINT dim_stat_pkey PRIMARY KEY (statkey)
)