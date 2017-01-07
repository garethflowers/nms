--
-- PostgreSQL database dump
--

-- Started on 2009-07-01 21:39:49

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 1844 (class 1262 OID 93610)
-- Name: nms; Type: DATABASE; Schema: -; Owner: -
--


SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 329 (class 1247 OID 93796)
-- Dependencies: 3 1517
-- Name: child_info; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE child_info AS (
	age integer,
	name character varying(500)
);


--
-- TOC entry 20 (class 1255 OID 93680)
-- Dependencies: 331 3
-- Name: age_months(date, date); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION age_months(_from date, _to date) RETURNS integer
    AS $$
BEGIN
    return extract(month from age(_to,_from)) + (extract(year from age(_to,_from)) * 12);
END;
$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


--
-- TOC entry 21 (class 1255 OID 93681)
-- Dependencies: 3 331
-- Name: age_months(date); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION age_months(_from date) RETURNS integer
    AS $$

BEGIN

	return extract(month from age(current_date,_from)) + (extract(year from age(current_date,_from)) * 12);
    
END;

$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


--
-- TOC entry 25 (class 1255 OID 93682)
-- Dependencies: 3 331
-- Name: date_open(date); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION date_open(_date date) RETURNS integer
    AS $$

DECLARE

	_open integer;
	
BEGIN

	select count(date) into _open
		from dates
		where (date=_date and repeat='N')
		or (repeat='Y'
		and extract(day from date)=extract(day from _date)
		and extract(month from date)=extract(month from _date));

	if _open > 0 then
		return 1;
	else
		return 0;
	end if;	
	
END

$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


--
-- TOC entry 24 (class 1255 OID 93883)
-- Dependencies: 331 3
-- Name: format_date(date); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION format_date(_date date) RETURNS character
    AS $$

BEGIN

	return to_char(_date, 'DD-MM-YYYY');

END

$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


--
-- TOC entry 23 (class 1255 OID 93797)
-- Dependencies: 329 3 331
-- Name: session_names(date, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION session_names(_date date, _session character varying, _room integer) RETURNS SETOF child_info
    AS $$

DECLARE

    _info child_info%rowtype;

BEGIN

    if date_open(_date) = 0 then
            
        for _info in select distinct age_months(dob, _date) as age, forename || ' ' || surname as name
            from child_session
            inner join child on child.id = child_session.child_id
            inner join rooms on rooms.id=_room
            where child.start <= _date
            and child.finish >= _date
            and child_session.date <= _date
            and child_session.date_till >= _date
            and extract(dow from child_session.date) = extract(dow from _date)
            and (child_session.session = _session or child_session.session = 'D')
            and child_session.exclude = 'N'
            and ((age_months(dob, _date) >= rooms.min and age_months(dob, _date) < rooms.max and (child.room_override = 0 or child.room_override is null)) or child.room_override = _room)
            except
            select distinct age_months(dob, _date) as age, forename || ' ' || surname as name
            from child_session
            inner join child on child.id = child_session.child_id
            inner join rooms on rooms.id=_room
            where child.start <= _date
            and child.finish >= _date
            and child_session.date <= _date
            and child_session.date_till >= _date
            and extract(dow from child_session.date) = extract(dow from _date)
            and (child_session.session = _session or child_session.session = 'D')
            and child_session.exclude = 'Y'
            and ((age_months(dob, _date) >= rooms.min and age_months(dob, _date) < rooms.max and (child.room_override = 0 or child.room_override is null)) or child.room_override = _room)
            order by age,name loop

            return next _info;
        
        end loop;

    end if;

END

$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


--
-- TOC entry 22 (class 1255 OID 93793)
-- Dependencies: 331 3
-- Name: session_numbers(date, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION session_numbers(_date date, _session character varying, _room integer) RETURNS integer
    AS $$

DECLARE

    _total_sessions integer;
    _total_excluded integer;
    _excluded_dates integer;

BEGIN

    if date_open(_date) = 0 then

        select count(child.id) into _total_sessions
            from child_session
            inner join child on child.id = child_session.child_id
            inner join rooms on rooms.id = _room
            where child.start <= _date
            and child.finish >= _date
            and child_session.date <= _date
            and child_session.date_till >= _date
            and extract(dow from child_session.date) = extract(dow from _date)
            and (child_session.session = _session or child_session.session = 'D')
            and child_session.exclude = 'N'
            and ((age_months(child.dob,_date) >= rooms.min and age_months(child.dob,_date) < rooms.max and (child.room_override = 0 or child.room_override is null)) or child.room_override = _room);

        if _total_sessions is null then
            _total_sessions = 0;
        end if;

        select count(child.id) into _total_excluded
            from child_session
            inner join child on child.id = child_session.child_id
            inner join rooms on rooms.id = _room
            where child.start <= _date
            and child.finish >= _date
            and child_session.date <= _date
            and child_session.date_till >= _date
            and extract(dow from child_session.date) = extract(dow from _date)
            and (child_session.session = _session or child_session.session = 'D')
            and child_session.exclude = 'Y'
            and ((age_months(child.dob,_date) >= rooms.min and age_months(child.dob,_date) < rooms.max and (child.room_override = 0 or child.room_override is null)) or child.room_override = _room);

        if _total_excluded is null then
            _total_excluded = 0;
        end if;

        return _total_sessions - _total_excluded;

    else

        return 0;

    end if;

END

$$
    LANGUAGE plpgsql IMMUTABLE STRICT;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1503 (class 1259 OID 93611)
-- Dependencies: 1790 3
-- Name: child; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE child (
    id integer NOT NULL,
    forename character varying(100) NOT NULL,
    dob date NOT NULL,
    surname character varying(100) NOT NULL,
    address character varying(500),
    city character varying(100),
    county character varying(100),
    postcode character varying(8),
    telephone character varying(11),
    next_kin character varying(200),
    gradual_admission date NOT NULL,
    start date NOT NULL,
    finish date NOT NULL,
    keyworker integer,
    backup_keyworker integer,
    pickup_password character varying(100),
    room_override integer,
    unborn boolean DEFAULT false NOT NULL,
    religion integer,
    nationality integer,
    notes text
);


--
-- TOC entry 1504 (class 1259 OID 93623)
-- Dependencies: 3
-- Name: child_contact; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE child_contact (
    id integer NOT NULL,
    child_id integer NOT NULL,
    name character varying(200) NOT NULL,
    occupation character varying(200),
    place_work character varying(200),
    work_telephone character varying(11),
    mobile character varying(11),
    address character varying(500),
    city character varying(200),
    county character varying(200),
    postcode character varying(8),
    telephone character varying(11),
    type character varying(1) NOT NULL,
    relationship integer
);


--
-- TOC entry 1511 (class 1259 OID 93686)
-- Dependencies: 1504 3
-- Name: child_contact_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE child_contact_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1847 (class 0 OID 0)
-- Dependencies: 1511
-- Name: child_contact_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE child_contact_id_seq OWNED BY child_contact.id;


--
-- TOC entry 1510 (class 1259 OID 93684)
-- Dependencies: 3 1503
-- Name: child_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE child_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1848 (class 0 OID 0)
-- Dependencies: 1510
-- Name: child_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE child_id_seq OWNED BY child.id;


--
-- TOC entry 1505 (class 1259 OID 93633)
-- Dependencies: 3
-- Name: child_session; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE child_session (
    id integer NOT NULL,
    child_id integer NOT NULL,
    session character varying(1) NOT NULL,
    date date NOT NULL,
    date_till date NOT NULL,
    extra boolean NOT NULL,
    exclude boolean NOT NULL
);


--
-- TOC entry 1512 (class 1259 OID 93688)
-- Dependencies: 1505 3
-- Name: child_session_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE child_session_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1849 (class 0 OID 0)
-- Dependencies: 1512
-- Name: child_session_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE child_session_id_seq OWNED BY child_session.id;


--
-- TOC entry 1506 (class 1259 OID 93640)
-- Dependencies: 3
-- Name: dates; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE dates (
    id integer NOT NULL,
    date date NOT NULL,
    repeat boolean NOT NULL
);


--
-- TOC entry 1513 (class 1259 OID 93690)
-- Dependencies: 1506 3
-- Name: dates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE dates_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1850 (class 0 OID 0)
-- Dependencies: 1513
-- Name: dates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE dates_id_seq OWNED BY dates.id;


--
-- TOC entry 1521 (class 1259 OID 93895)
-- Dependencies: 3
-- Name: nationality; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE nationality (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


--
-- TOC entry 1520 (class 1259 OID 93893)
-- Dependencies: 3 1521
-- Name: nationality_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nationality_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1851 (class 0 OID 0)
-- Dependencies: 1520
-- Name: nationality_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nationality_id_seq OWNED BY nationality.id;


--
-- TOC entry 1523 (class 1259 OID 93962)
-- Dependencies: 3
-- Name: relationship; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE relationship (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


--
-- TOC entry 1522 (class 1259 OID 93960)
-- Dependencies: 3 1523
-- Name: relationship_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE relationship_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1852 (class 0 OID 0)
-- Dependencies: 1522
-- Name: relationship_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE relationship_id_seq OWNED BY relationship.id;


--
-- TOC entry 1519 (class 1259 OID 93887)
-- Dependencies: 3
-- Name: religion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE religion (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


--
-- TOC entry 1518 (class 1259 OID 93885)
-- Dependencies: 3 1519
-- Name: religion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE religion_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1853 (class 0 OID 0)
-- Dependencies: 1518
-- Name: religion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE religion_id_seq OWNED BY religion.id;


--
-- TOC entry 1507 (class 1259 OID 93644)
-- Dependencies: 1795 1796 3
-- Name: rooms; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE rooms (
    id integer NOT NULL,
    name character varying(200) NOT NULL,
    total smallint NOT NULL,
    min smallint NOT NULL,
    max smallint NOT NULL,
    include boolean NOT NULL,
    price_session numeric(10,2) DEFAULT 0.00 NOT NULL,
    price_day numeric(10,2) DEFAULT 0.00 NOT NULL
);


--
-- TOC entry 1514 (class 1259 OID 93694)
-- Dependencies: 3 1507
-- Name: rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE rooms_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1854 (class 0 OID 0)
-- Dependencies: 1514
-- Name: rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE rooms_id_seq OWNED BY rooms.id;


--
-- TOC entry 1508 (class 1259 OID 93662)
-- Dependencies: 1798 3
-- Name: staff; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE staff (
    id integer NOT NULL,
    surname character varying(100) NOT NULL,
    forename character varying(100) NOT NULL,
    address character varying(500),
    city character varying(100),
    county character varying(100),
    postcode character varying(8),
    telephone character varying(11),
    mobile character varying(11),
    email character varying(200),
    dob date,
    national_insurance character varying(15),
    next_of_kin character varying(200),
    doctor character varying(200),
    doctor_address character varying(500),
    vehicle_registration character varying(10),
    bank_name character varying(200),
    bank_account character varying(20),
    bank_sort_code character varying(20),
    username character varying(20) NOT NULL,
    password character varying(20) NOT NULL,
    assigned_room integer,
    start date,
    finish date,
    notes text,
    userlevel integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 1509 (class 1259 OID 93674)
-- Dependencies: 3
-- Name: staff_contact; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE staff_contact (
    id integer NOT NULL,
    staff_id integer,
    name character varying(200),
    telephone character varying(11),
    address character varying(500),
    city character varying(100),
    county character varying(100),
    postcode character varying(8),
    mobile character varying(11),
    relationship integer
);


--
-- TOC entry 1515 (class 1259 OID 93696)
-- Dependencies: 3 1509
-- Name: staff_contact_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE staff_contact_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1855 (class 0 OID 0)
-- Dependencies: 1515
-- Name: staff_contact_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE staff_contact_id_seq OWNED BY staff_contact.id;


--
-- TOC entry 1516 (class 1259 OID 93698)
-- Dependencies: 3 1508
-- Name: staff_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE staff_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1856 (class 0 OID 0)
-- Dependencies: 1516
-- Name: staff_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE staff_id_seq OWNED BY staff.id;


--
-- TOC entry 1791 (class 2604 OID 93700)
-- Dependencies: 1510 1503
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE child ALTER COLUMN id SET DEFAULT nextval('child_id_seq'::regclass);


--
-- TOC entry 1792 (class 2604 OID 93701)
-- Dependencies: 1511 1504
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE child_contact ALTER COLUMN id SET DEFAULT nextval('child_contact_id_seq'::regclass);


--
-- TOC entry 1793 (class 2604 OID 93702)
-- Dependencies: 1512 1505
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE child_session ALTER COLUMN id SET DEFAULT nextval('child_session_id_seq'::regclass);


--
-- TOC entry 1794 (class 2604 OID 93703)
-- Dependencies: 1513 1506
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE dates ALTER COLUMN id SET DEFAULT nextval('dates_id_seq'::regclass);


--
-- TOC entry 1802 (class 2604 OID 93898)
-- Dependencies: 1520 1521 1521
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE nationality ALTER COLUMN id SET DEFAULT nextval('nationality_id_seq'::regclass);


--
-- TOC entry 1803 (class 2604 OID 93965)
-- Dependencies: 1522 1523 1523
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE relationship ALTER COLUMN id SET DEFAULT nextval('relationship_id_seq'::regclass);


--
-- TOC entry 1801 (class 2604 OID 93890)
-- Dependencies: 1518 1519 1519
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE religion ALTER COLUMN id SET DEFAULT nextval('religion_id_seq'::regclass);


--
-- TOC entry 1797 (class 2604 OID 93704)
-- Dependencies: 1514 1507
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE rooms ALTER COLUMN id SET DEFAULT nextval('rooms_id_seq'::regclass);


--
-- TOC entry 1799 (class 2604 OID 93706)
-- Dependencies: 1516 1508
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE staff ALTER COLUMN id SET DEFAULT nextval('staff_id_seq'::regclass);


--
-- TOC entry 1800 (class 2604 OID 93707)
-- Dependencies: 1515 1509
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE staff_contact ALTER COLUMN id SET DEFAULT nextval('staff_contact_id_seq'::regclass);


--
-- TOC entry 1810 (class 2606 OID 93709)
-- Dependencies: 1504 1504
-- Name: child_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY child_contact
    ADD CONSTRAINT child_contact_pkey PRIMARY KEY (id);


--
-- TOC entry 1807 (class 2606 OID 93711)
-- Dependencies: 1503 1503
-- Name: child_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_pkey PRIMARY KEY (id);


--
-- TOC entry 1814 (class 2606 OID 93713)
-- Dependencies: 1505 1505
-- Name: child_session_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY child_session
    ADD CONSTRAINT child_session_pkey PRIMARY KEY (id);


--
-- TOC entry 1830 (class 2606 OID 93900)
-- Dependencies: 1521 1521
-- Name: nationality_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY nationality
    ADD CONSTRAINT nationality_pkey PRIMARY KEY (id);


--
-- TOC entry 1832 (class 2606 OID 93967)
-- Dependencies: 1523 1523
-- Name: relationship_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY relationship
    ADD CONSTRAINT relationship_pkey PRIMARY KEY (id);


--
-- TOC entry 1828 (class 2606 OID 93892)
-- Dependencies: 1519 1519
-- Name: religion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY religion
    ADD CONSTRAINT religion_pkey PRIMARY KEY (id);


--
-- TOC entry 1817 (class 2606 OID 93715)
-- Dependencies: 1506 1506
-- Name: settings_dates_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY dates
    ADD CONSTRAINT settings_dates_pkey PRIMARY KEY (id);


--
-- TOC entry 1820 (class 2606 OID 93719)
-- Dependencies: 1507 1507
-- Name: settings_rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rooms
    ADD CONSTRAINT settings_rooms_pkey PRIMARY KEY (id);


--
-- TOC entry 1826 (class 2606 OID 93721)
-- Dependencies: 1509 1509
-- Name: staff_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY staff_contact
    ADD CONSTRAINT staff_contact_pkey PRIMARY KEY (id);


--
-- TOC entry 1823 (class 2606 OID 93723)
-- Dependencies: 1508 1508
-- Name: staff_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY staff
    ADD CONSTRAINT staff_pkey PRIMARY KEY (id);


--
-- TOC entry 1808 (class 1259 OID 93724)
-- Dependencies: 1504 1504
-- Name: child_contact_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX child_contact_idx1 ON child_contact USING btree (id, child_id);


--
-- TOC entry 1804 (class 1259 OID 93725)
-- Dependencies: 1503 1503
-- Name: child_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX child_idx1 ON child USING btree (id, dob);


--
-- TOC entry 1805 (class 1259 OID 94226)
-- Dependencies: 1503 1503
-- Name: child_idx2; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX child_idx2 ON child USING btree (start, finish);


--
-- TOC entry 1811 (class 1259 OID 93727)
-- Dependencies: 1505 1505
-- Name: child_session_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX child_session_idx1 ON child_session USING btree (id, child_id);


--
-- TOC entry 1812 (class 1259 OID 93728)
-- Dependencies: 1505 1505
-- Name: child_session_idx2; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX child_session_idx2 ON child_session USING btree (date, date_till);


--
-- TOC entry 1815 (class 1259 OID 93729)
-- Dependencies: 1506
-- Name: dates_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX dates_idx1 ON dates USING btree (date);


--
-- TOC entry 1818 (class 1259 OID 93730)
-- Dependencies: 1507 1507
-- Name: rooms_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX rooms_idx1 ON rooms USING btree (min, max);


--
-- TOC entry 1824 (class 1259 OID 93731)
-- Dependencies: 1509 1509
-- Name: staff_contact_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX staff_contact_idx1 ON staff_contact USING btree (id, staff_id);


--
-- TOC entry 1821 (class 1259 OID 94227)
-- Dependencies: 1508 1508
-- Name: staff_idx1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX staff_idx1 ON staff USING btree (start, finish);


--
-- TOC entry 1834 (class 2606 OID 93788)
-- Dependencies: 1508 1822 1503
-- Name: child_backup_worker_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_backup_worker_fkey FOREIGN KEY (backup_keyworker) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1838 (class 2606 OID 93968)
-- Dependencies: 1806 1503 1504
-- Name: child_contact_child_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child_contact
    ADD CONSTRAINT child_contact_child_id_fkey FOREIGN KEY (child_id) REFERENCES child(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1839 (class 2606 OID 93996)
-- Dependencies: 1523 1831 1504
-- Name: child_contact_relationship_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child_contact
    ADD CONSTRAINT child_contact_relationship_fkey FOREIGN KEY (relationship) REFERENCES relationship(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1833 (class 2606 OID 93783)
-- Dependencies: 1508 1503 1822
-- Name: child_keyworker_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_keyworker_fkey FOREIGN KEY (keyworker) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1837 (class 2606 OID 93906)
-- Dependencies: 1829 1503 1521
-- Name: child_nationality_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_nationality_fkey FOREIGN KEY (nationality) REFERENCES nationality(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1836 (class 2606 OID 93901)
-- Dependencies: 1503 1519 1827
-- Name: child_religion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_religion_fkey FOREIGN KEY (religion) REFERENCES religion(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1835 (class 2606 OID 93878)
-- Dependencies: 1819 1503 1507
-- Name: child_room_override_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child
    ADD CONSTRAINT child_room_override_fkey FOREIGN KEY (room_override) REFERENCES rooms(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1840 (class 2606 OID 93973)
-- Dependencies: 1806 1503 1505
-- Name: child_session_child_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY child_session
    ADD CONSTRAINT child_session_child_id_fkey FOREIGN KEY (child_id) REFERENCES child(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1841 (class 2606 OID 94176)
-- Dependencies: 1508 1509 1822
-- Name: staff_contact_staff_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY staff_contact
    ADD CONSTRAINT staff_contact_staff_id_fkey FOREIGN KEY (staff_id) REFERENCES staff(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1846 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2009-07-01 21:39:49

--
-- PostgreSQL database dump complete
--

