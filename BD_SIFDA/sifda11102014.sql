--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: dblink; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS dblink WITH SCHEMA public;


--
-- Name: EXTENSION dblink; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION dblink IS 'connect to other PostgreSQL databases from within a database';


SET search_path = public, pg_catalog;

--
-- Name: anios_sin_cargar(); Type: FUNCTION; Schema: public; Owner: sifda
--

CREATE FUNCTION anios_sin_cargar() RETURNS SETOF integer
    LANGUAGE plpgsql
    AS $$
BEGIN

EXECUTE 'SELECT dblink_connect(''sidpla'', ''dbname=sidpla'')';

    RETURN QUERY SELECT DISTINCT(anio) FROM dblink('sidpla','SELECT le.anio FROM sidpla_linea_estrategica le ') AS t(anio int) 
WHERE anio NOT IN (SELECT DISTINCT(le.anio) FROM sidpla_linea_estrategica le) 
AND anio > (SELECT MAX(le.anio) FROM sidpla_linea_estrategica le) ORDER BY anio;

EXECUTE 'SELECT dblink_disconnect(''sidpla'')';    
    RETURN;
 END
$$;


ALTER FUNCTION public.anios_sin_cargar() OWNER TO sifda;

--
-- Name: cargar_data_sidpla(integer); Type: FUNCTION; Schema: public; Owner: sifda
--

CREATE FUNCTION cargar_data_sidpla(anio integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN 
EXECUTE 'SELECT dblink_connect(''sidpla'', ''dbname=sidpla'')';

EXECUTE 'INSERT INTO sidpla_linea_estrategica (SELECT id,id_dependencia_establecimiento,descripcion,
codigo,activo,anio,recurrente FROM dblink(''sidpla'',''SELECT le.id,le.id_dependencia_establecimiento,le.descripcion,
le.codigo,le.activo,le.anio,le.recurrente FROM sidpla_linea_estrategica le  WHERE le.anio ='||anio||''') 
AS t(id int,id_dependencia_establecimiento int,descripcion text,codigo text,activo boolean,
anio int,recurrente boolean))';

EXECUTE 'INSERT INTO sidpla_actividad (SELECT id,id_linea_estrategica,id_empleado,descripcion,
codigo,activo,meta_anual,descripcion_meta_anual,indicador,medio_verificacion,false AS generado
FROM dblink(''sidpla'',''SELECT a.id,a.id_linea_estrategica,a.id_empleado,a.descripcion,
a.codigo,a.activo,a.meta_anual,a.descripcion_meta_anual,a.indicador,a.medio_verificacion FROM sidpla_actividad a 
LEFT OUTER JOIN sidpla_linea_estrategica le ON le.id = a.id_linea_estrategica WHERE le.anio ='||anio||''') 
AS t(id int,id_linea_estrategica int,id_empleado int,descripcion text,codigo text,activo boolean,
meta_anual numeric,descripcion_meta_anual text,indicador text,medio_verificacion text))';

EXECUTE 'INSERT INTO sidpla_subactividad (SELECT id,id_actividad,id_empleado,descripcion,
codigo,activo,meta_anual,descripcion_meta_anual,indicador,medio_verificacion 
FROM dblink(''sidpla'',''SELECT s.id,s.id_actividad,s.id_empleado,s.descripcion,
s.codigo,s.activo,s.meta_anual,s.descripcion_meta_anual,s.indicador,s.medio_verificacion FROM sidpla_subactividad s 
LEFT OUTER JOIN sidpla_actividad a ON a.id = s.id_actividad 
LEFT OUTER JOIN sidpla_linea_estrategica le ON le.id = a.id_linea_estrategica WHERE le.anio ='||anio||''') 
AS t(id int,id_actividad int,id_empleado int,descripcion text,codigo text,activo boolean,
meta_anual numeric,descripcion_meta_anual text,indicador text,medio_verificacion text))';

EXECUTE 'SELECT dblink_disconnect(''sidpla'')';
RETURN TRUE;
EXCEPTION WHEN unique_violation THEN
	EXECUTE 'SELECT dblink_disconnect(''sidpla'')';
	RETURN FALSE;
END; $$;


ALTER FUNCTION public.cargar_data_sidpla(anio integer) OWNER TO sifda;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bitacora; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE bitacora (
    id integer NOT NULL,
    user_id integer,
    id_evento integer,
    fecha_evento date NOT NULL,
    observacion text NOT NULL
);


ALTER TABLE public.bitacora OWNER TO sifda;

--
-- Name: bitacora_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE bitacora_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.bitacora_id_seq OWNER TO sifda;

--
-- Name: catalogo; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE catalogo (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion character varying(150) NOT NULL,
    sistema integer NOT NULL,
    ref1 character varying(20) NOT NULL
);


ALTER TABLE public.catalogo OWNER TO sifda;

--
-- Name: catalogo_detalle; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE catalogo_detalle (
    id integer NOT NULL,
    id_catalogo integer,
    descripcion character varying(100) NOT NULL,
    ref1 character varying(20) NOT NULL,
    estatus boolean NOT NULL
);


ALTER TABLE public.catalogo_detalle OWNER TO sifda;

--
-- Name: catalogo_detalle_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE catalogo_detalle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.catalogo_detalle_id_seq OWNER TO sifda;

--
-- Name: catalogo_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE catalogo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.catalogo_id_seq OWNER TO sifda;

--
-- Name: ctl_cargo; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_cargo (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL
);


ALTER TABLE public.ctl_cargo OWNER TO sifda;

--
-- Name: ctl_cargo_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_cargo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_cargo_id_seq OWNER TO sifda;

--
-- Name: ctl_dependencia; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_dependencia (
    id integer NOT NULL,
    id_tipo_dependencia integer,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.ctl_dependencia OWNER TO sifda;

--
-- Name: ctl_dependencia_establecimiento; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_dependencia_establecimiento (
    id integer NOT NULL,
    id_establecimiento integer,
    id_dependencia integer,
    id_dependencia_padre integer,
    abreviatura character varying(10),
    habilitado boolean NOT NULL
);


ALTER TABLE public.ctl_dependencia_establecimiento OWNER TO sifda;

--
-- Name: ctl_dependencia_establecimiento_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_dependencia_establecimiento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_dependencia_establecimiento_id_seq OWNER TO sifda;

--
-- Name: ctl_dependencia_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_dependencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_dependencia_id_seq OWNER TO sifda;

--
-- Name: ctl_empleado; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_empleado (
    id integer NOT NULL,
    id_dependencia_establecimiento integer,
    id_cargo integer,
    nombre character varying(100) NOT NULL,
    apellido character varying(100) NOT NULL,
    fecha_nacimiento date NOT NULL,
    correo_electronico character varying(50) NOT NULL
);


ALTER TABLE public.ctl_empleado OWNER TO sifda;

--
-- Name: ctl_empleado_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_empleado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_empleado_id_seq OWNER TO sifda;

--
-- Name: ctl_establecimiento; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_establecimiento (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.ctl_establecimiento OWNER TO sifda;

--
-- Name: ctl_establecimiento_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_establecimiento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_establecimiento_id_seq OWNER TO sifda;

--
-- Name: ctl_tipo_dependencia; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE ctl_tipo_dependencia (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.ctl_tipo_dependencia OWNER TO sifda;

--
-- Name: ctl_tipo_dependencia_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE ctl_tipo_dependencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ctl_tipo_dependencia_id_seq OWNER TO sifda;

--
-- Name: fos_user_group; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE fos_user_group (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    roles text NOT NULL
);


ALTER TABLE public.fos_user_group OWNER TO sifda;

--
-- Name: TABLE fos_user_group; Type: COMMENT; Schema: public; Owner: sifda
--

COMMENT ON TABLE fos_user_group IS 'Maneja los grupo de roles para el BUNDLE SONATAADMINBUNDLE de symfony';


--
-- Name: COLUMN fos_user_group.roles; Type: COMMENT; Schema: public; Owner: sifda
--

COMMENT ON COLUMN fos_user_group.roles IS '(DC2Type:array)';


--
-- Name: fos_user_group_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE fos_user_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fos_user_group_id_seq OWNER TO sifda;

--
-- Name: fos_user_user; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE fos_user_user (
    id integer NOT NULL,
    id_dependencia_establecimiento integer,
    id_empleado integer,
    username character varying(255) NOT NULL,
    username_canonical character varying(255) DEFAULT NULL::character varying NOT NULL,
    email character varying(255) NOT NULL,
    email_canonical character varying(255) DEFAULT NULL::character varying NOT NULL,
    enabled boolean NOT NULL,
    salt character varying(255) DEFAULT NULL::character varying NOT NULL,
    password character varying(255) NOT NULL,
    last_login timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    locked boolean NOT NULL,
    expired boolean NOT NULL,
    expires_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    confirmation_token character varying(255) DEFAULT NULL::character varying,
    password_requested_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    roles text NOT NULL,
    credentials_expired boolean NOT NULL,
    credentials_expire_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    created_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone NOT NULL,
    date_of_birth timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    firstname character varying(64),
    lastname character varying(64),
    website character varying(64) DEFAULT NULL::character varying,
    biography character varying(1000) DEFAULT NULL::character varying,
    gender character varying(1) DEFAULT NULL::character varying,
    locale character varying(8) DEFAULT NULL::character varying,
    timezone character varying(64) DEFAULT NULL::character varying,
    phone character varying(64) DEFAULT NULL::character varying,
    facebook_uid character varying(255) DEFAULT NULL::character varying,
    facebook_name character varying(255) DEFAULT NULL::character varying,
    facebook_data text,
    twitter_uid character varying(255) DEFAULT NULL::character varying,
    twitter_name character varying(255) DEFAULT NULL::character varying,
    twitter_data text,
    gplus_uid character varying(255) DEFAULT NULL::character varying,
    gplus_name character varying(255) DEFAULT NULL::character varying,
    gplus_data text,
    token character varying(255) DEFAULT NULL::character varying,
    two_step_code character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.fos_user_user OWNER TO sifda;

--
-- Name: TABLE fos_user_user; Type: COMMENT; Schema: public; Owner: sifda
--

COMMENT ON TABLE fos_user_user IS 'Maneja los usuarios tanto para los módulos en Symfony como para los de PHP puro';


--
-- Name: fos_user_user_group; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE fos_user_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.fos_user_user_group OWNER TO sifda;

--
-- Name: TABLE fos_user_user_group; Type: COMMENT; Schema: public; Owner: sifda
--

COMMENT ON TABLE fos_user_user_group IS 'Tabla intermedia para saber que usuarios poseen que grupos dentro de los modulos con Symfony';


--
-- Name: fos_user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE fos_user_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fos_user_user_id_seq OWNER TO sifda;

--
-- Name: sidpla_actividad; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sidpla_actividad (
    id integer NOT NULL,
    id_linea_estrategica integer,
    id_empleado integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    activo boolean NOT NULL,
    meta_anual numeric(5,2) NOT NULL,
    descripcion_meta_anual character varying(50) NOT NULL,
    indicador text NOT NULL,
    medio_verificacion character varying(300) NOT NULL,
    generado boolean
);


ALTER TABLE public.sidpla_actividad OWNER TO sifda;

--
-- Name: sidpla_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sidpla_actividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sidpla_actividad_id_seq OWNER TO sifda;

--
-- Name: sidpla_linea_estrategica; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sidpla_linea_estrategica (
    id integer NOT NULL,
    id_dependencia_establecimiento integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    activo boolean NOT NULL,
    anio integer NOT NULL,
    recurrente boolean NOT NULL
);


ALTER TABLE public.sidpla_linea_estrategica OWNER TO sifda;

--
-- Name: sidpla_linea_estrategica_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sidpla_linea_estrategica_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sidpla_linea_estrategica_id_seq OWNER TO sifda;

--
-- Name: sidpla_subactividad; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sidpla_subactividad (
    id integer NOT NULL,
    id_actividad integer,
    id_empleado integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    activo boolean NOT NULL,
    meta_anual numeric(5,2) NOT NULL,
    descripcion_meta_anual character varying(50) NOT NULL,
    indicador text NOT NULL,
    medio_verificacion character varying(300)
);


ALTER TABLE public.sidpla_subactividad OWNER TO sifda;

--
-- Name: sidpla_subactividad_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sidpla_subactividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sidpla_subactividad_id_seq OWNER TO sifda;

--
-- Name: sifda_detalle_solicitud_orden; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_detalle_solicitud_orden (
    id integer NOT NULL,
    id_detalle_solicitud_servicio integer,
    id_orden_trabajo integer
);


ALTER TABLE public.sifda_detalle_solicitud_orden OWNER TO sifda;

--
-- Name: sifda_detalle_solicitud_orden_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_detalle_solicitud_orden_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_detalle_solicitud_orden_id_seq OWNER TO sifda;

--
-- Name: sifda_detalle_solicitud_servicio; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_detalle_solicitud_servicio (
    id integer NOT NULL,
    id_solicitud_servicio integer,
    descripcion text NOT NULL,
    cantidad_solicitada integer NOT NULL,
    cantidad_aprobada integer,
    justificacion text
);


ALTER TABLE public.sifda_detalle_solicitud_servicio OWNER TO sifda;

--
-- Name: sifda_detalle_solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_detalle_solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_detalle_solicitud_servicio_id_seq OWNER TO sifda;

--
-- Name: sifda_equipo_trabajo; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_equipo_trabajo (
    id integer NOT NULL,
    id_orden_trabajo integer,
    id_empleado integer,
    responsable_equipo boolean NOT NULL
);


ALTER TABLE public.sifda_equipo_trabajo OWNER TO sifda;

--
-- Name: sifda_equipo_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_equipo_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_equipo_trabajo_id_seq OWNER TO sifda;

--
-- Name: sifda_informe_orden_trabajo; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_informe_orden_trabajo (
    id integer NOT NULL,
    id_empleado integer,
    id_orden_trabajo integer,
    id_subactividad integer,
    id_dependencia_establecimiento integer,
    id_etapa integer,
    detalle text NOT NULL,
    fecha_realizacion timestamp without time zone NOT NULL,
    fecha_registro timestamp(0) without time zone NOT NULL,
    terminado boolean NOT NULL
);


ALTER TABLE public.sifda_informe_orden_trabajo OWNER TO sifda;

--
-- Name: sifda_informe_orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_informe_orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_informe_orden_trabajo_id_seq OWNER TO sifda;

--
-- Name: sifda_orden_trabajo; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_orden_trabajo (
    id integer NOT NULL,
    id_solicitud_servicio integer,
    id_estado integer,
    id_etapa integer,
    id_dependencia_establecimiento integer,
    id_prioridad integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    fecha_creacion timestamp(0) without time zone NOT NULL,
    fecha_finalizacion timestamp without time zone,
    observacion text
);


ALTER TABLE public.sifda_orden_trabajo OWNER TO sifda;

--
-- Name: sifda_orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_orden_trabajo_id_seq OWNER TO sifda;

--
-- Name: sifda_recurso_servicio; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_recurso_servicio (
    id integer NOT NULL,
    id_informe_orden_trabajo integer,
    id_tipo_recurso_dependencia integer,
    cantidad integer NOT NULL,
    costo_total double precision NOT NULL
);


ALTER TABLE public.sifda_recurso_servicio OWNER TO sifda;

--
-- Name: sifda_recurso_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_recurso_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_recurso_servicio_id_seq OWNER TO sifda;

--
-- Name: sifda_reprogramacion_servicio; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_reprogramacion_servicio (
    id integer NOT NULL,
    id_solicitud_servicio integer,
    fecha_reprogramacion date NOT NULL,
    justificacion text NOT NULL
);


ALTER TABLE public.sifda_reprogramacion_servicio OWNER TO sifda;

--
-- Name: sifda_reprogramacion_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_reprogramacion_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_reprogramacion_servicio_id_seq OWNER TO sifda;

--
-- Name: sifda_retraso_actividad; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_retraso_actividad (
    id integer NOT NULL,
    id_orden_trabajo integer,
    razon_retraso text NOT NULL
);


ALTER TABLE public.sifda_retraso_actividad OWNER TO sifda;

--
-- Name: sifda_retraso_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_retraso_actividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_retraso_actividad_id_seq OWNER TO sifda;

--
-- Name: sifda_ruta; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_ruta (
    id integer NOT NULL,
    id_tipo_servicio integer,
    descripcion text NOT NULL,
    tipo character varying(75) NOT NULL
);


ALTER TABLE public.sifda_ruta OWNER TO sifda;

--
-- Name: sifda_ruta_ciclo_vida; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_ruta_ciclo_vida (
    id integer NOT NULL,
    id_tipo_servicio integer,
    id_etapa integer,
    descripcion text NOT NULL,
    referencia text,
    jerarquia integer NOT NULL,
    ignorar_sig boolean NOT NULL,
    peso integer NOT NULL
);


ALTER TABLE public.sifda_ruta_ciclo_vida OWNER TO sifda;

--
-- Name: sifda_ruta_ciclo_vida_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_ruta_ciclo_vida_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_ruta_ciclo_vida_id_seq OWNER TO sifda;

--
-- Name: sifda_ruta_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_ruta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_ruta_id_seq OWNER TO sifda;

--
-- Name: sifda_solicitud_servicio; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_solicitud_servicio (
    id integer NOT NULL,
    id_tipo_servicio integer,
    user_id integer,
    id_dependencia_establecimiento integer,
    id_estado integer,
    id_medio_solicita integer,
    descripcion text NOT NULL,
    fecha_recepcion timestamp(0) without time zone NOT NULL,
    fecha_requiere timestamp without time zone
);


ALTER TABLE public.sifda_solicitud_servicio OWNER TO sifda;

--
-- Name: sifda_solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_solicitud_servicio_id_seq OWNER TO sifda;

--
-- Name: sifda_tipo_recurso; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_tipo_recurso (
    id integer NOT NULL,
    nombre character varying(30) NOT NULL,
    descripcion text NOT NULL,
    rrhh boolean NOT NULL
);


ALTER TABLE public.sifda_tipo_recurso OWNER TO sifda;

--
-- Name: sifda_tipo_recurso_dependencia; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_tipo_recurso_dependencia (
    id integer NOT NULL,
    id_tipo_recurso integer,
    id_dependencia_establecimiento integer,
    costo_unitario double precision NOT NULL
);


ALTER TABLE public.sifda_tipo_recurso_dependencia OWNER TO sifda;

--
-- Name: sifda_tipo_recurso_dependencia_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_tipo_recurso_dependencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_tipo_recurso_dependencia_id_seq OWNER TO sifda;

--
-- Name: sifda_tipo_recurso_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_tipo_recurso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_tipo_recurso_id_seq OWNER TO sifda;

--
-- Name: sifda_tipo_servicio; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_tipo_servicio (
    id integer NOT NULL,
    id_actividad integer,
    nombre character varying(75) NOT NULL,
    descripcion text NOT NULL,
    activo boolean NOT NULL,
    id_dependencia_establecimiento integer
);


ALTER TABLE public.sifda_tipo_servicio OWNER TO sifda;

--
-- Name: sifda_tipo_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_tipo_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_tipo_servicio_id_seq OWNER TO sifda;

--
-- Name: sifda_tracking_estado; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sifda_tracking_estado (
    id integer NOT NULL,
    id_orden_trabajo integer,
    id_estado integer,
    id_etapa integer,
    fecha_inicio timestamp(0) without time zone NOT NULL,
    fecha_fin timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    prog_actividad character varying(40) NOT NULL,
    observacion text NOT NULL
);


ALTER TABLE public.sifda_tracking_estado OWNER TO sifda;

--
-- Name: sifda_tracking_estado_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE sifda_tracking_estado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sifda_tracking_estado_id_seq OWNER TO sifda;

--
-- Name: vwetapassolicitud; Type: VIEW; Schema: public; Owner: sifda
--

CREATE VIEW vwetapassolicitud AS
    SELECT row_number() OVER () AS id, ss.id AS id_solicitud, ss.descripcion AS dsc_solicitud, ss.fecha_recepcion AS fchrecep_solicitud, ss.fecha_requiere AS fchreq_solicitud, ts.id AS id_tipo_servicio, ts.nombre AS nombre_tipo_servicio, ts.descripcion AS dsc_tipo_servicio, rcv.id AS id_ciclo_vida, rcv.jerarquia AS jerar_ciclo_vida, rcv.descripcion AS dsc_ciclo_vida, rcv.peso AS etapa_peso, rcv.ignorar_sig AS ignorar_sig_etapa, srcv.id AS id_subetapa, srcv.descripcion AS dsc_subetapa, srcv.peso AS subetapa_peso, srcv.ignorar_sig AS ignorar_sig_subetapa, ot.id AS id_orden, ot.descripcion AS dsc_orden, ot.fecha_creacion AS fchcrea_orden, ot.fecha_finalizacion AS fchfin_orden, COALESCE(cd.id, 0) AS id_estado, COALESCE(cd.descripcion, 'Sin Asignar'::character varying) AS dsc_estado, e.id AS id_empleado, (((e.nombre)::text || ' '::text) || (e.apellido)::text) AS nom_empleado FROM (((((((sifda_solicitud_servicio ss LEFT JOIN sifda_tipo_servicio ts ON ((ss.id_tipo_servicio = ts.id))) LEFT JOIN sifda_ruta_ciclo_vida rcv ON ((ts.id = rcv.id_tipo_servicio))) LEFT JOIN sifda_orden_trabajo ot ON (((ot.id_etapa = rcv.id) AND (ot.id_solicitud_servicio = ss.id)))) LEFT JOIN catalogo_detalle cd ON ((cd.id = ot.id_estado))) LEFT JOIN sifda_equipo_trabajo et ON (((et.id_orden_trabajo = ot.id) AND (et.responsable_equipo = true)))) LEFT JOIN ctl_empleado e ON ((et.id_empleado = e.id))) LEFT JOIN (SELECT subetapa.id, subetapa.id_etapa, subetapa.descripcion, subetapa.jerarquia, subetapa.peso, subetapa.ignorar_sig FROM sifda_ruta_ciclo_vida subetapa WHERE (subetapa.id_etapa IS NOT NULL) ORDER BY subetapa.jerarquia) srcv ON ((srcv.id_etapa = rcv.id))) WHERE (rcv.id_etapa IS NULL) ORDER BY rcv.jerarquia;


ALTER TABLE public.vwetapassolicitud OWNER TO sifda;

--
-- Data for Name: bitacora; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY bitacora (id, user_id, id_evento, fecha_evento, observacion) FROM stdin;
\.


--
-- Name: bitacora_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('bitacora_id_seq', 1, false);


--
-- Data for Name: catalogo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY catalogo (id, nombre, descripcion, sistema, ref1) FROM stdin;
1	Medio	Es el medio por el cual solicita un servicio	1	medio solicita
2	Estados Solicitud	Muestra los estados de una solicitud de servio 1	1	formas del servicio
3	Prioridad	Muestra la prioridad de una actividad	1	prioridad actividad
\.


--
-- Data for Name: catalogo_detalle; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY catalogo_detalle (id, id_catalogo, descripcion, ref1, estatus) FROM stdin;
1	2	Ingresado		t
2	2	Asignado		t
3	2	Rechazado		t
4	2	Finalizado		t
5	1	Sistema		t
6	1	PAO		t
7	1	Verbal		t
8	1	Correo Electronico		t
9	3	Alta		t
10	3	Media	' '	t
11	3	Baja		t
12	3	Urgente		t
\.


--
-- Name: catalogo_detalle_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('catalogo_detalle_id_seq', 1, false);


--
-- Name: catalogo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('catalogo_id_seq', 1, true);


--
-- Data for Name: ctl_cargo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_cargo (id, nombre) FROM stdin;
1	Técnico 1
2	Programador
4	Técnico 3
3	Técnico 2
\.


--
-- Name: ctl_cargo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_cargo_id_seq', 1, false);


--
-- Data for Name: ctl_dependencia; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_dependencia (id, id_tipo_dependencia, nombre) FROM stdin;
2	1	Unidad de Adquisiciones y Contrat. Inst.(UACI)
3	1	Unidad Financiera Institucional(UFI)
4	1	Dirección de Tecnologias de Información y Comunicaciones
5	1	Unidad de Administración de Recursos Humanos
6	1	Farmacia
7	1	Centro Nacional de Biologicos
8	1	Clinica de Empleados
9	1	Dirección de Vigilancia Sanitaria
10	1	Almacenes
11	1	Departamento de Mantenimiento General
12	1	Departamento lab. Productos Biologicos
13	1	Departamento Servicios Auxiliares
14	1	Depto. de Correspondencia y Archivo
15	1	Unidad de Salud Mental
16	1	Laboratorio Control de Calidad
17	1	Dirección General de Salud
18	1	Gerencia General de Operaciones (Administración)
19	1	Direccion de Planificacion
20	1	Direccion de Regulacion y Legislación en Salud
21	1	Despacho Ministerial
23	1	Unidad de Conservacion y Mantenimiento
24	1	Gerencia de Atencion al Adolescente
25	1	Programa de Atencion al Adulto Masculino
26	1	Programa Atencion al Adulto Mayor
27	1	Gerencia de Atencion Materno Infantil
28	1	Dirección de Salud Ambiental
31	1	Programa Nacional de Tuberculosis y Enfermedades Respiratorias
32	1	Programa Nacional ITS/VIH/SIDA
33	1	Proyecto Fortalecimiento de la Salud
34	1	Seccion de Contabilidad
35	1	Seccion de Impresiones
36	1	Seccion de Presupuesto
37	1	Seccion de Tesoreria
38	1	Seccion Distribucion de Vehiculos
39	1	Unidad de Promoción de la Salud
40	1	Unidad de Vigilancia de Enfermedades Vectorizadas
41	1	UCP- RESSHA
42	1	Unidad Coord. de Programas BID(PAM)
43	1	Unidad de Asesoria Juridica
44	1	Unidad de Auditoria Interna
45	1	Unidad de Comunicaciones
46	1	Unidad de Cooperacion Externa
47	1	Unidad de Emergencia y Desastres
48	1	Unidad de Enfermeria
49	1	Unidad de Epidemiologia
50	1	Unidad de Fondos Externos
51	1	Unidad de Informacion en Salud
52	1	Unidad de Ingenieria de Salud
53	1	Unidad de Proyectos
54	1	Laboratorio Nacional de Referencia
55	1	Unidad de Patrimonio
56	1	Unidad de Zoonosis
57	1	Unidad Reguladora y Asesora de Radiaciones
58	1	Almacén de Medicamentos
59	1	Almacén de Insumos Médicos
60	1	Almacén de Suministros Generales
61	1	Almacén de Equipos
62	1	Unidad de Salud Bucal
63	1	Dirección General
65	1	Sede Regional
66	1	Proyecto Fortalecimiento de la Salud y Educación Básica 
67	1	Abt Associates Inc.
68	1	JICA
69	1	Proyecto de Reconstrucción de Hospitales RHESSA
70	1	Programa Lesiones de Causa Externa
71	1	Unidad por el derecho a la Salud
72	1	Programa Atención en Salud a la Niñez
73	1	Unidad de Salud Sexual y Reproductiva
74	1	Unidad de Salud Comunitaria
75	1	Consulta Externa
76	1	Estadística ESDOMED
77	1	Establecimiento Externo
78	1	Secretaria de Estado
80	1	Almacén Central El Paraiso
81	1	Laboratorio de Control de Calidad de Medicamentos
82	1	Programa Nacional de Lepra
83	1	Comisión Intersectorial de Salud (CISALUD)
84	1	Servicios Generales
85	1	Unidad Ejecutora - Fondo Global
86	1	Comisión Nacional Contra el SIDA
87	1	Dirección de Primer Nivel de Atención
88	1	Dirección Nacional de Hospitales
89	1	Dirección de Apoyo a la Gestión y Programacion Sanitaria
90	1	Viceministerio de Servicios de Salud
91	1	Viceministerio de Políticas de Salud
92	1	Unidad de Abastecimientos
93	1	Unidad de Nutrición
94	1	Dirección de Desarrollo de RRHH
95	1	Unidad de Atención a la Violencia
96	1	Dirección de Enfermedades Infecciosas
97	1	Oficina de Información y Respuesta
98	1	Hospitalización
99	1	Emergencia
100	1	Instituto Nacional de Salud
101	1	Direccion de Emergencias Medicas
102	1	CONAIPD
103	1	Corte de Cuentas de la Republica
1	1	Dirección de Medicamentos y Dispositivos Médicos
\.


--
-- Data for Name: ctl_dependencia_establecimiento; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_dependencia_establecimiento (id, id_establecimiento, id_dependencia, id_dependencia_padre, abreviatura, habilitado) FROM stdin;
2	2	1	\N	\N	t
3	2	3	\N	\N	t
5	2	5	\N	\N	t
6	2	7	\N	\N	t
7	2	8	\N	\N	t
8	2	9	\N	\N	t
9	2	10	\N	\N	t
10	2	11	\N	\N	t
11	2	12	\N	\N	t
12	2	13	\N	\N	t
13	2	14	\N	\N	t
14	2	15	\N	\N	t
15	2	16	\N	\N	t
16	2	17	\N	\N	t
17	2	18	\N	\N	t
18	2	19	\N	\N	t
19	2	20	\N	\N	t
20	2	21	\N	\N	t
21	2	23	\N	\N	t
22	2	24	\N	\N	t
23	2	25	\N	\N	t
24	2	26	\N	\N	t
25	2	27	\N	\N	t
26	2	28	\N	\N	t
27	2	31	\N	\N	t
28	2	32	\N	\N	t
29	2	33	\N	\N	t
30	2	34	\N	\N	t
31	2	35	\N	\N	t
32	2	36	\N	\N	t
33	2	37	\N	\N	t
34	2	38	\N	\N	t
35	2	39	\N	\N	t
36	2	40	\N	\N	t
37	2	41	\N	\N	t
38	2	42	\N	\N	t
39	2	43	\N	\N	t
40	2	44	\N	\N	t
41	2	45	\N	\N	t
42	2	46	\N	\N	t
43	2	47	\N	\N	t
44	2	48	\N	\N	t
45	2	49	\N	\N	t
46	2	50	\N	\N	t
47	2	51	\N	\N	t
48	2	52	\N	\N	t
49	2	53	\N	\N	t
50	2	54	\N	\N	t
51	2	55	\N	\N	t
52	2	56	\N	\N	t
53	2	57	\N	\N	t
54	2	58	\N	\N	t
55	2	59	\N	\N	t
56	2	60	\N	\N	t
57	2	61	\N	\N	t
58	2	62	\N	\N	t
59	2	70	\N	\N	t
60	2	71	\N	\N	t
61	2	72	\N	\N	t
62	2	73	\N	\N	t
63	2	74	\N	\N	t
64	2	80	\N	\N	t
65	2	81	\N	\N	t
66	2	82	\N	\N	t
67	2	83	\N	\N	t
68	2	84	\N	\N	t
69	2	85	\N	\N	t
70	2	86	\N	\N	t
71	2	87	\N	\N	t
72	2	88	\N	\N	t
73	2	89	\N	\N	t
74	2	90	\N	\N	t
75	2	91	\N	\N	t
76	2	92	\N	\N	t
77	2	93	\N	\N	t
78	2	94	\N	\N	t
79	2	95	\N	\N	t
80	2	96	\N	\N	t
81	2	97	\N	\N	t
82	2	100	\N	\N	t
83	2	101	\N	\N	t
84	3	5	\N	\N	t
85	3	10	\N	\N	t
86	3	65	\N	\N	t
87	3	76	\N	\N	t
88	4	5	\N	\N	t
89	4	10	\N	\N	t
90	4	65	\N	\N	t
91	4	76	\N	\N	t
92	5	5	\N	\N	t
93	5	10	\N	\N	t
94	5	65	\N	\N	t
95	5	76	\N	\N	t
96	6	5	\N	\N	t
97	6	10	\N	\N	t
98	6	65	\N	\N	t
99	6	76	\N	\N	t
100	7	5	\N	\N	t
101	7	10	\N	\N	t
102	7	65	\N	\N	t
103	7	76	\N	\N	t
104	9	63	\N	\N	t
105	10	63	\N	\N	t
106	11	63	\N	\N	t
107	12	63	\N	\N	t
108	13	63	\N	\N	t
109	14	63	\N	\N	t
110	15	63	\N	\N	t
111	16	63	\N	\N	t
112	17	63	\N	\N	t
113	18	63	\N	\N	t
114	19	63	\N	\N	t
115	20	63	\N	\N	t
116	21	63	\N	\N	t
117	22	63	\N	\N	t
118	23	63	\N	\N	t
119	24	63	\N	\N	t
120	25	63	\N	\N	t
121	26	63	\N	\N	t
122	27	63	\N	\N	t
123	28	63	\N	\N	t
124	29	63	\N	\N	t
125	30	63	\N	\N	t
126	31	63	\N	\N	t
127	32	63	\N	\N	t
128	33	63	\N	\N	t
129	34	63	\N	\N	t
130	35	63	\N	\N	t
131	36	3	\N	\N	t
132	36	4	\N	\N	t
133	36	5	\N	\N	t
134	36	6	\N	\N	t
135	36	10	\N	\N	t
136	36	18	\N	\N	t
137	36	23	\N	\N	t
138	36	63	\N	\N	t
139	36	75	\N	\N	t
140	36	76	\N	\N	t
141	36	84	\N	\N	t
142	36	98	\N	\N	t
143	36	99	\N	\N	t
144	37	63	\N	\N	t
145	38	63	\N	\N	t
146	39	3	\N	\N	t
147	39	4	\N	\N	t
148	39	6	\N	\N	t
149	39	10	\N	\N	t
150	39	18	\N	\N	t
151	39	23	\N	\N	t
152	39	63	\N	\N	t
153	39	75	\N	\N	t
154	39	76	\N	\N	t
155	39	84	\N	\N	t
156	39	98	\N	\N	t
157	39	99	\N	\N	t
158	40	63	\N	\N	t
159	41	63	\N	\N	t
160	42	3	\N	\N	t
161	42	4	\N	\N	t
162	42	5	\N	\N	t
163	42	6	\N	\N	t
164	42	10	\N	\N	t
165	42	18	\N	\N	t
166	42	23	\N	\N	t
167	42	63	\N	\N	t
168	42	75	\N	\N	t
169	42	76	\N	\N	t
170	42	84	\N	\N	t
171	42	98	\N	\N	t
172	42	99	\N	\N	t
173	43	63	\N	\N	t
174	44	63	\N	\N	t
175	45	63	\N	\N	t
176	46	63	\N	\N	t
177	47	63	\N	\N	t
178	48	63	\N	\N	t
179	49	63	\N	\N	t
180	50	63	\N	\N	t
181	51	63	\N	\N	t
182	52	63	\N	\N	t
183	53	63	\N	\N	t
184	54	63	\N	\N	t
185	55	63	\N	\N	t
186	56	63	\N	\N	t
187	57	63	\N	\N	t
188	58	63	\N	\N	t
189	59	63	\N	\N	t
190	60	63	\N	\N	t
191	61	63	\N	\N	t
192	62	63	\N	\N	t
193	63	63	\N	\N	t
194	64	63	\N	\N	t
195	65	63	\N	\N	t
196	66	63	\N	\N	t
197	67	63	\N	\N	t
198	68	63	\N	\N	t
199	69	63	\N	\N	t
200	70	63	\N	\N	t
201	71	63	\N	\N	t
202	72	63	\N	\N	t
203	73	63	\N	\N	t
204	74	63	\N	\N	t
205	75	63	\N	\N	t
206	76	63	\N	\N	t
207	77	63	\N	\N	t
208	78	63	\N	\N	t
209	79	63	\N	\N	t
210	80	63	\N	\N	t
211	81	63	\N	\N	t
212	82	63	\N	\N	t
213	83	63	\N	\N	t
214	84	63	\N	\N	t
215	85	63	\N	\N	t
216	86	63	\N	\N	t
217	87	63	\N	\N	t
218	88	63	\N	\N	t
219	89	63	\N	\N	t
220	90	63	\N	\N	t
221	91	63	\N	\N	t
222	92	63	\N	\N	t
223	93	63	\N	\N	t
224	94	63	\N	\N	t
225	95	63	\N	\N	t
226	96	63	\N	\N	t
227	97	63	\N	\N	t
228	98	63	\N	\N	t
229	100	63	\N	\N	t
230	100	63	\N	\N	t
231	101	63	\N	\N	t
232	102	63	\N	\N	t
233	103	63	\N	\N	t
234	104	63	\N	\N	t
235	105	63	\N	\N	t
236	106	63	\N	\N	t
237	107	63	\N	\N	t
238	108	3	\N	\N	t
239	108	4	\N	\N	t
240	108	5	\N	\N	t
241	108	6	\N	\N	t
242	108	10	\N	\N	t
243	108	18	\N	\N	t
244	108	23	\N	\N	t
245	108	63	\N	\N	t
246	108	75	\N	\N	t
247	108	76	\N	\N	t
248	108	84	\N	\N	t
249	108	98	\N	\N	t
250	108	99	\N	\N	t
251	109	63	\N	\N	t
252	110	63	\N	\N	t
253	111	63	\N	\N	t
254	112	3	\N	\N	t
255	112	4	\N	\N	t
256	112	5	\N	\N	t
257	112	6	\N	\N	t
258	112	10	\N	\N	t
259	112	18	\N	\N	t
260	112	23	\N	\N	t
261	112	63	\N	\N	t
262	112	75	\N	\N	t
263	112	76	\N	\N	t
264	112	84	\N	\N	t
265	112	98	\N	\N	t
266	112	99	\N	\N	t
267	113	63	\N	\N	t
268	114	63	\N	\N	t
269	115	63	\N	\N	t
270	116	63	\N	\N	t
271	117	63	\N	\N	t
272	118	63	\N	\N	t
273	119	63	\N	\N	t
274	120	63	\N	\N	t
275	121	63	\N	\N	t
276	122	63	\N	\N	t
277	123	63	\N	\N	t
278	124	63	\N	\N	t
279	125	63	\N	\N	t
280	126	63	\N	\N	t
281	127	63	\N	\N	t
282	128	63	\N	\N	t
283	129	63	\N	\N	t
284	130	63	\N	\N	t
285	131	63	\N	\N	t
286	132	63	\N	\N	t
287	133	63	\N	\N	t
288	134	63	\N	\N	t
289	135	63	\N	\N	t
290	136	63	\N	\N	t
291	137	63	\N	\N	t
292	138	63	\N	\N	t
293	139	63	\N	\N	t
294	140	63	\N	\N	t
295	141	63	\N	\N	t
296	142	63	\N	\N	t
297	143	63	\N	\N	t
298	144	63	\N	\N	t
299	145	63	\N	\N	t
300	146	63	\N	\N	t
301	147	63	\N	\N	t
302	148	63	\N	\N	t
303	149	63	\N	\N	t
304	150	63	\N	\N	t
305	151	63	\N	\N	t
306	152	63	\N	\N	t
307	153	63	\N	\N	t
308	154	63	\N	\N	t
309	155	63	\N	\N	t
310	156	63	\N	\N	t
311	157	63	\N	\N	t
312	158	63	\N	\N	t
313	159	63	\N	\N	t
314	160	63	\N	\N	t
315	161	63	\N	\N	t
316	162	63	\N	\N	t
317	163	63	\N	\N	t
318	164	63	\N	\N	t
319	165	3	\N	\N	t
320	165	4	\N	\N	t
321	165	5	\N	\N	t
322	165	6	\N	\N	t
323	165	10	\N	\N	t
324	165	18	\N	\N	t
325	165	23	\N	\N	t
326	165	63	\N	\N	t
327	165	75	\N	\N	t
328	165	76	\N	\N	t
329	165	84	\N	\N	t
330	165	98	\N	\N	t
331	165	99	\N	\N	t
332	166	63	\N	\N	t
333	167	63	\N	\N	t
334	168	63	\N	\N	t
335	169	63	\N	\N	t
336	170	63	\N	\N	t
337	171	63	\N	\N	t
338	172	63	\N	\N	t
339	173	63	\N	\N	t
340	174	63	\N	\N	t
341	175	63	\N	\N	t
342	176	63	\N	\N	t
343	177	63	\N	\N	t
344	178	63	\N	\N	t
345	179	63	\N	\N	t
346	180	63	\N	\N	t
347	181	63	\N	\N	t
348	182	63	\N	\N	t
349	183	63	\N	\N	t
350	184	63	\N	\N	t
351	185	63	\N	\N	t
352	186	63	\N	\N	t
353	187	63	\N	\N	t
354	188	63	\N	\N	t
355	189	63	\N	\N	t
356	190	63	\N	\N	t
357	191	63	\N	\N	t
358	192	63	\N	\N	t
359	193	63	\N	\N	t
360	194	63	\N	\N	t
361	195	63	\N	\N	t
362	196	63	\N	\N	t
363	197	63	\N	\N	t
364	198	63	\N	\N	t
365	199	63	\N	\N	t
366	200	63	\N	\N	t
367	201	63	\N	\N	t
368	202	63	\N	\N	t
369	203	63	\N	\N	t
370	204	3	\N	\N	t
371	204	4	\N	\N	t
372	204	5	\N	\N	t
373	204	6	\N	\N	t
374	204	10	\N	\N	t
375	204	18	\N	\N	t
376	204	23	\N	\N	t
377	204	63	\N	\N	t
378	204	75	\N	\N	t
379	204	76	\N	\N	t
380	204	84	\N	\N	t
381	204	98	\N	\N	t
382	204	99	\N	\N	t
383	205	63	\N	\N	t
384	206	63	\N	\N	t
385	207	3	\N	\N	t
386	207	4	\N	\N	t
387	207	5	\N	\N	t
388	207	6	\N	\N	t
389	207	10	\N	\N	t
390	207	18	\N	\N	t
391	207	23	\N	\N	t
392	207	63	\N	\N	t
393	207	75	\N	\N	t
394	207	76	\N	\N	t
395	207	84	\N	\N	t
396	207	98	\N	\N	t
397	207	99	\N	\N	t
398	208	63	\N	\N	t
399	209	63	\N	\N	t
400	210	63	\N	\N	t
401	211	63	\N	\N	t
402	212	63	\N	\N	t
403	213	63	\N	\N	t
404	214	63	\N	\N	t
405	215	63	\N	\N	t
406	216	63	\N	\N	t
407	217	63	\N	\N	t
408	218	63	\N	\N	t
409	219	63	\N	\N	t
410	220	63	\N	\N	t
411	221	63	\N	\N	t
412	222	63	\N	\N	t
413	223	63	\N	\N	t
414	224	63	\N	\N	t
415	224	63	\N	\N	t
416	226	63	\N	\N	t
417	227	63	\N	\N	t
418	228	3	\N	\N	t
419	228	4	\N	\N	t
420	228	5	\N	\N	t
421	228	6	\N	\N	t
422	228	10	\N	\N	t
423	228	18	\N	\N	t
424	228	23	\N	\N	t
425	228	63	\N	\N	t
426	228	75	\N	\N	t
427	228	76	\N	\N	t
428	228	84	\N	\N	t
429	228	98	\N	\N	t
430	228	99	\N	\N	t
431	229	63	\N	\N	t
432	230	63	\N	\N	t
433	231	63	\N	\N	t
434	232	63	\N	\N	t
435	233	63	\N	\N	t
436	234	63	\N	\N	t
1102	235	63	\N	\N	t
437	236	63	\N	\N	t
438	237	63	\N	\N	t
439	238	63	\N	\N	t
440	239	63	\N	\N	t
441	240	63	\N	\N	t
442	241	63	\N	\N	t
443	242	63	\N	\N	t
444	243	63	\N	\N	t
445	244	63	\N	\N	t
446	245	63	\N	\N	t
447	246	63	\N	\N	t
448	247	63	\N	\N	t
449	247	84	\N	\N	t
450	248	63	\N	\N	t
451	249	63	\N	\N	t
452	250	63	\N	\N	t
453	251	63	\N	\N	t
454	252	63	\N	\N	t
455	253	63	\N	\N	t
456	254	63	\N	\N	t
457	255	63	\N	\N	t
458	256	63	\N	\N	t
459	257	63	\N	\N	t
460	258	63	\N	\N	t
461	259	63	\N	\N	t
462	260	63	\N	\N	t
463	261	63	\N	\N	t
464	262	63	\N	\N	t
465	263	63	\N	\N	t
466	264	63	\N	\N	t
467	265	63	\N	\N	t
468	266	63	\N	\N	t
469	267	63	\N	\N	t
470	268	63	\N	\N	t
471	269	63	\N	\N	t
472	270	63	\N	\N	t
473	271	63	\N	\N	t
474	272	63	\N	\N	t
475	273	63	\N	\N	t
476	274	3	\N	\N	t
477	274	4	\N	\N	t
478	274	5	\N	\N	t
479	274	6	\N	\N	t
480	274	10	\N	\N	t
481	274	18	\N	\N	t
482	274	23	\N	\N	t
483	274	63	\N	\N	t
484	274	75	\N	\N	t
485	274	76	\N	\N	t
486	274	98	\N	\N	t
487	274	99	\N	\N	t
488	275	63	\N	\N	t
489	276	63	\N	\N	t
490	277	3	\N	\N	t
491	277	4	\N	\N	t
492	277	5	\N	\N	t
493	277	6	\N	\N	t
494	277	10	\N	\N	t
495	277	18	\N	\N	t
496	277	23	\N	\N	t
497	277	63	\N	\N	t
498	277	75	\N	\N	t
499	277	76	\N	\N	t
500	277	84	\N	\N	t
501	277	98	\N	\N	t
502	277	99	\N	\N	t
503	278	63	\N	\N	t
504	279	63	\N	\N	t
505	280	63	\N	\N	t
506	281	63	\N	\N	t
507	282	63	\N	\N	t
508	283	63	\N	\N	t
509	284	63	\N	\N	t
510	285	63	\N	\N	t
511	286	63	\N	\N	t
512	287	63	\N	\N	t
513	288	63	\N	\N	t
514	289	63	\N	\N	t
515	290	63	\N	\N	t
516	291	63	\N	\N	t
517	292	63	\N	\N	t
518	293	63	\N	\N	t
519	294	63	\N	\N	t
520	295	63	\N	\N	t
521	296	63	\N	\N	t
522	297	63	\N	\N	t
523	298	63	\N	\N	t
524	299	63	\N	\N	t
525	300	63	\N	\N	t
526	301	63	\N	\N	t
527	302	63	\N	\N	t
528	303	63	\N	\N	t
529	304	63	\N	\N	t
530	305	63	\N	\N	t
531	306	63	\N	\N	t
532	307	63	\N	\N	t
533	308	63	\N	\N	t
534	309	63	\N	\N	t
535	310	3	\N	\N	t
536	310	4	\N	\N	t
537	310	5	\N	\N	t
538	310	6	\N	\N	t
539	310	10	\N	\N	t
540	310	18	\N	\N	t
541	310	23	\N	\N	t
542	310	63	\N	\N	t
543	310	75	\N	\N	t
544	310	76	\N	\N	t
545	310	84	\N	\N	t
546	310	98	\N	\N	t
547	310	99	\N	\N	t
548	311	63	\N	\N	t
549	312	63	\N	\N	t
550	313	63	\N	\N	t
551	314	63	\N	\N	t
552	315	63	\N	\N	t
553	316	63	\N	\N	t
554	317	63	\N	\N	t
555	318	63	\N	\N	t
556	319	63	\N	\N	t
557	320	63	\N	\N	t
558	321	63	\N	\N	t
559	322	63	\N	\N	t
560	323	63	\N	\N	t
561	324	63	\N	\N	t
562	325	63	\N	\N	t
563	326	63	\N	\N	t
564	327	63	\N	\N	t
565	328	63	\N	\N	t
566	329	63	\N	\N	t
567	330	63	\N	\N	t
568	331	63	\N	\N	t
569	332	3	\N	\N	t
570	332	4	\N	\N	t
571	332	5	\N	\N	t
572	332	6	\N	\N	t
573	332	10	\N	\N	t
574	332	18	\N	\N	t
575	332	23	\N	\N	t
576	332	63	\N	\N	t
577	332	75	\N	\N	t
578	332	76	\N	\N	t
579	332	84	\N	\N	t
580	332	98	\N	\N	t
581	332	99	\N	\N	t
582	333	63	\N	\N	t
583	334	63	\N	\N	t
584	335	63	\N	\N	t
585	336	3	\N	\N	t
586	336	4	\N	\N	t
587	336	5	\N	\N	t
588	336	6	\N	\N	t
589	336	10	\N	\N	t
590	336	18	\N	\N	t
591	336	23	\N	\N	t
592	336	63	\N	\N	t
593	336	75	\N	\N	t
594	336	76	\N	\N	t
595	336	84	\N	\N	t
596	336	98	\N	\N	t
597	336	99	\N	\N	t
598	337	63	\N	\N	t
599	338	63	\N	\N	t
600	339	63	\N	\N	t
601	340	3	\N	\N	t
602	340	4	\N	\N	t
603	340	5	\N	\N	t
604	340	6	\N	\N	t
605	340	10	\N	\N	t
606	340	18	\N	\N	t
607	340	23	\N	\N	t
608	340	63	\N	\N	t
609	340	75	\N	\N	t
610	340	76	\N	\N	t
611	340	84	\N	\N	t
612	340	98	\N	\N	t
613	340	99	\N	\N	t
614	341	63	\N	\N	t
615	342	63	\N	\N	t
616	343	63	\N	\N	t
617	344	63	\N	\N	t
618	345	63	\N	\N	t
619	346	63	\N	\N	t
620	347	63	\N	\N	t
621	348	63	\N	\N	t
622	349	63	\N	\N	t
623	350	63	\N	\N	t
624	351	63	\N	\N	t
625	352	63	\N	\N	t
626	353	63	\N	\N	t
627	354	63	\N	\N	t
628	355	63	\N	\N	t
629	356	63	\N	\N	t
630	357	63	\N	\N	t
631	358	63	\N	\N	t
632	359	63	\N	\N	t
633	360	63	\N	\N	t
634	361	63	\N	\N	t
635	362	63	\N	\N	t
636	363	63	\N	\N	t
637	364	63	\N	\N	t
638	365	63	\N	\N	t
639	366	63	\N	\N	t
640	367	63	\N	\N	t
641	368	63	\N	\N	t
642	369	63	\N	\N	t
643	370	63	\N	\N	t
644	371	63	\N	\N	t
645	372	63	\N	\N	t
646	373	63	\N	\N	t
647	374	63	\N	\N	t
648	375	63	\N	\N	t
649	376	63	\N	\N	t
650	377	63	\N	\N	t
651	378	63	\N	\N	t
652	379	63	\N	\N	t
653	380	3	\N	\N	t
654	380	4	\N	\N	t
655	380	5	\N	\N	t
656	380	6	\N	\N	t
657	380	10	\N	\N	t
658	380	18	\N	\N	t
659	380	23	\N	\N	t
660	380	63	\N	\N	t
661	380	75	\N	\N	t
662	380	76	\N	\N	t
663	380	84	\N	\N	t
664	380	98	\N	\N	t
665	380	99	\N	\N	t
666	381	63	\N	\N	t
667	382	63	\N	\N	t
668	383	3	\N	\N	t
669	383	4	\N	\N	t
670	383	5	\N	\N	t
671	383	6	\N	\N	t
672	383	10	\N	\N	t
673	383	18	\N	\N	t
674	383	23	\N	\N	t
675	383	63	\N	\N	t
676	383	75	\N	\N	t
677	383	76	\N	\N	t
678	383	84	\N	\N	t
679	383	98	\N	\N	t
680	383	99	\N	\N	t
681	384	63	\N	\N	t
682	385	63	\N	\N	t
683	386	3	\N	\N	t
684	386	4	\N	\N	t
685	386	5	\N	\N	t
686	386	6	\N	\N	t
687	386	10	\N	\N	t
688	386	18	\N	\N	t
689	386	23	\N	\N	t
690	386	63	\N	\N	t
691	386	75	\N	\N	t
692	386	76	\N	\N	t
693	386	84	\N	\N	t
694	386	98	\N	\N	t
695	386	99	\N	\N	t
696	387	63	\N	\N	t
697	388	63	\N	\N	t
698	389	63	\N	\N	t
699	390	63	\N	\N	t
700	391	63	\N	\N	t
701	392	63	\N	\N	t
702	393	63	\N	\N	t
703	394	63	\N	\N	t
704	395	63	\N	\N	t
705	396	63	\N	\N	t
706	397	63	\N	\N	t
707	398	63	\N	\N	t
708	399	63	\N	\N	t
709	400	63	\N	\N	t
710	401	63	\N	\N	t
711	402	63	\N	\N	t
712	403	63	\N	\N	t
713	404	63	\N	\N	t
714	405	63	\N	\N	t
715	406	63	\N	\N	t
716	407	63	\N	\N	t
717	408	63	\N	\N	t
718	409	63	\N	\N	t
719	410	63	\N	\N	t
720	411	63	\N	\N	t
721	412	63	\N	\N	t
722	413	63	\N	\N	t
723	414	63	\N	\N	t
724	415	63	\N	\N	t
725	416	63	\N	\N	t
726	417	63	\N	\N	t
727	418	63	\N	\N	t
728	419	63	\N	\N	t
729	420	63	\N	\N	t
730	421	63	\N	\N	t
731	422	63	\N	\N	t
732	423	63	\N	\N	t
733	424	63	\N	\N	t
734	425	63	\N	\N	t
735	426	63	\N	\N	t
736	427	63	\N	\N	t
737	428	63	\N	\N	t
738	429	63	\N	\N	t
739	430	63	\N	\N	t
740	431	63	\N	\N	t
741	432	63	\N	\N	t
742	433	63	\N	\N	t
743	434	3	\N	\N	t
744	434	4	\N	\N	t
745	434	5	\N	\N	t
746	434	6	\N	\N	t
747	434	10	\N	\N	t
748	434	18	\N	\N	t
749	434	23	\N	\N	t
750	434	63	\N	\N	t
751	434	75	\N	\N	t
752	434	76	\N	\N	t
753	434	84	\N	\N	t
754	434	98	\N	\N	t
755	434	99	\N	\N	t
756	435	63	\N	\N	t
757	436	63	\N	\N	t
758	437	63	\N	\N	t
759	438	63	\N	\N	t
760	439	63	\N	\N	t
761	440	63	\N	\N	t
762	441	63	\N	\N	t
763	442	63	\N	\N	t
764	443	63	\N	\N	t
765	444	63	\N	\N	t
766	445	63	\N	\N	t
767	446	63	\N	\N	t
768	447	63	\N	\N	t
769	448	63	\N	\N	t
770	449	63	\N	\N	t
771	450	63	\N	\N	t
772	451	63	\N	\N	t
773	452	63	\N	\N	t
774	453	63	\N	\N	t
775	454	63	\N	\N	t
776	455	63	\N	\N	t
777	456	63	\N	\N	t
778	457	63	\N	\N	t
779	458	63	\N	\N	t
780	459	63	\N	\N	t
781	460	63	\N	\N	t
782	461	63	\N	\N	t
783	462	63	\N	\N	t
784	463	63	\N	\N	t
785	464	63	\N	\N	t
786	465	63	\N	\N	t
787	466	63	\N	\N	t
788	467	3	\N	\N	t
789	467	4	\N	\N	t
790	467	5	\N	\N	t
791	467	6	\N	\N	t
792	467	10	\N	\N	t
793	467	18	\N	\N	t
794	467	23	\N	\N	t
795	467	63	\N	\N	t
796	467	75	\N	\N	t
797	467	76	\N	\N	t
798	467	84	\N	\N	t
799	467	98	\N	\N	t
800	467	99	\N	\N	t
801	468	63	\N	\N	t
802	469	63	\N	\N	t
803	470	63	\N	\N	t
804	471	3	\N	\N	t
805	471	4	\N	\N	t
806	471	5	\N	\N	t
807	471	6	\N	\N	t
808	471	10	\N	\N	t
809	471	18	\N	\N	t
810	471	23	\N	\N	t
811	471	63	\N	\N	t
812	471	75	\N	\N	t
813	471	76	\N	\N	t
814	471	84	\N	\N	t
815	471	98	\N	\N	t
816	471	99	\N	\N	t
817	472	63	\N	\N	t
818	473	63	\N	\N	t
819	474	63	\N	\N	t
820	475	63	\N	\N	t
821	476	63	\N	\N	t
822	477	63	\N	\N	t
823	478	63	\N	\N	t
824	479	63	\N	\N	t
825	480	63	\N	\N	t
826	481	63	\N	\N	t
827	482	63	\N	\N	t
828	483	63	\N	\N	t
829	484	63	\N	\N	t
830	485	63	\N	\N	t
831	486	63	\N	\N	t
832	487	63	\N	\N	t
833	488	63	\N	\N	t
834	489	63	\N	\N	t
835	490	63	\N	\N	t
836	491	63	\N	\N	t
837	492	63	\N	\N	t
838	493	63	\N	\N	t
839	494	63	\N	\N	t
840	495	63	\N	\N	t
841	496	63	\N	\N	t
842	497	63	\N	\N	t
843	498	63	\N	\N	t
844	499	63	\N	\N	t
845	500	63	\N	\N	t
846	501	63	\N	\N	t
847	502	63	\N	\N	t
848	503	63	\N	\N	t
849	504	63	\N	\N	t
850	505	63	\N	\N	t
851	506	63	\N	\N	t
852	507	63	\N	\N	t
853	508	63	\N	\N	t
854	509	63	\N	\N	t
855	510	63	\N	\N	t
856	511	63	\N	\N	t
857	512	3	\N	\N	t
858	512	4	\N	\N	t
859	512	5	\N	\N	t
860	512	6	\N	\N	t
861	512	10	\N	\N	t
862	512	18	\N	\N	t
863	512	23	\N	\N	t
864	512	63	\N	\N	t
865	512	75	\N	\N	t
866	512	76	\N	\N	t
867	512	84	\N	\N	t
868	512	98	\N	\N	t
869	512	99	\N	\N	t
870	513	63	\N	\N	t
871	514	63	\N	\N	t
872	515	63	\N	\N	t
873	516	63	\N	\N	t
874	517	63	\N	\N	t
875	518	3	\N	\N	t
876	518	4	\N	\N	t
877	518	5	\N	\N	t
878	518	6	\N	\N	t
879	518	10	\N	\N	t
880	518	18	\N	\N	t
881	518	23	\N	\N	t
882	518	63	\N	\N	t
883	518	75	\N	\N	t
884	518	76	\N	\N	t
885	518	84	\N	\N	t
886	518	98	\N	\N	t
887	518	99	\N	\N	t
888	519	63	\N	\N	t
889	520	63	\N	\N	t
890	521	63	\N	\N	t
891	522	63	\N	\N	t
892	523	3	\N	\N	t
893	523	4	\N	\N	t
894	523	6	\N	\N	t
895	523	10	\N	\N	t
896	523	18	\N	\N	t
897	523	23	\N	\N	t
898	523	63	\N	\N	t
899	523	75	\N	\N	t
900	523	76	\N	\N	t
901	523	84	\N	\N	t
902	523	98	\N	\N	t
903	523	99	\N	\N	t
904	524	63	\N	\N	t
905	525	63	\N	\N	t
906	526	63	\N	\N	t
907	527	63	\N	\N	t
908	528	3	\N	\N	t
909	528	4	\N	\N	t
910	528	5	\N	\N	t
911	528	6	\N	\N	t
912	528	10	\N	\N	t
913	528	18	\N	\N	t
914	528	23	\N	\N	t
915	528	63	\N	\N	t
916	528	75	\N	\N	t
917	528	76	\N	\N	t
918	528	84	\N	\N	t
919	528	98	\N	\N	t
920	528	99	\N	\N	t
921	529	63	\N	\N	t
922	530	63	\N	\N	t
923	531	63	\N	\N	t
924	532	63	\N	\N	t
925	533	3	\N	\N	t
926	533	4	\N	\N	t
927	533	5	\N	\N	t
928	533	6	\N	\N	t
929	533	10	\N	\N	t
930	533	18	\N	\N	t
931	533	23	\N	\N	t
932	533	63	\N	\N	t
933	533	75	\N	\N	t
934	533	76	\N	\N	t
935	533	84	\N	\N	t
936	533	98	\N	\N	t
937	533	99	\N	\N	t
938	534	63	\N	\N	t
939	535	63	\N	\N	t
940	536	3	\N	\N	t
941	536	4	\N	\N	t
942	536	5	\N	\N	t
943	536	6	\N	\N	t
944	536	10	\N	\N	t
945	536	18	\N	\N	t
946	536	23	\N	\N	t
947	536	63	\N	\N	t
948	536	75	\N	\N	t
949	536	76	\N	\N	t
950	536	84	\N	\N	t
951	536	98	\N	\N	t
952	536	99	\N	\N	t
953	537	63	\N	\N	t
954	538	63	\N	\N	t
955	539	3	\N	\N	t
956	539	4	\N	\N	t
957	539	5	\N	\N	t
958	539	6	\N	\N	t
959	539	10	\N	\N	t
960	539	18	\N	\N	t
961	539	23	\N	\N	t
962	539	55	\N	\N	t
963	539	63	\N	\N	t
964	539	75	\N	\N	t
965	539	76	\N	\N	t
966	539	84	\N	\N	t
967	539	98	\N	\N	t
968	539	99	\N	\N	t
969	540	63	\N	\N	t
970	541	63	\N	\N	t
971	542	63	\N	\N	t
972	543	63	\N	\N	t
973	544	63	\N	\N	t
974	545	63	\N	\N	t
975	546	63	\N	\N	t
976	547	63	\N	\N	t
977	548	63	\N	\N	t
978	549	63	\N	\N	t
979	550	63	\N	\N	t
980	551	63	\N	\N	t
981	552	63	\N	\N	t
982	553	63	\N	\N	t
983	554	63	\N	\N	t
984	555	63	\N	\N	t
985	556	63	\N	\N	t
986	557	63	\N	\N	t
987	558	63	\N	\N	t
988	559	63	\N	\N	t
989	560	63	\N	\N	t
990	561	63	\N	\N	t
991	562	63	\N	\N	t
992	563	63	\N	\N	t
993	564	63	\N	\N	t
994	565	63	\N	\N	t
995	566	63	\N	\N	t
996	567	63	\N	\N	t
997	568	63	\N	\N	t
998	569	63	\N	\N	t
999	570	63	\N	\N	t
1000	571	63	\N	\N	t
1001	572	63	\N	\N	t
1002	573	63	\N	\N	t
1003	574	63	\N	\N	t
1004	575	63	\N	\N	t
1005	576	63	\N	\N	t
1006	577	63	\N	\N	t
1007	578	63	\N	\N	t
1008	579	63	\N	\N	t
1009	580	63	\N	\N	t
1010	581	63	\N	\N	t
1011	582	63	\N	\N	t
1012	583	63	\N	\N	t
1013	584	63	\N	\N	t
1014	585	63	\N	\N	t
1015	586	63	\N	\N	t
1016	587	63	\N	\N	t
1017	588	63	\N	\N	t
1018	589	63	\N	\N	t
1019	590	63	\N	\N	t
1020	591	63	\N	\N	t
1021	592	63	\N	\N	t
1022	593	63	\N	\N	t
1023	594	63	\N	\N	t
1024	595	63	\N	\N	t
1025	596	63	\N	\N	t
1026	597	63	\N	\N	t
1027	598	63	\N	\N	t
1028	599	63	\N	\N	t
1029	600	63	\N	\N	t
1030	601	63	\N	\N	t
1031	602	63	\N	\N	t
1032	603	63	\N	\N	t
1033	604	63	\N	\N	t
1034	605	63	\N	\N	t
1035	606	63	\N	\N	t
1036	607	63	\N	\N	t
1037	608	63	\N	\N	t
1038	609	63	\N	\N	t
1039	610	63	\N	\N	t
1040	611	63	\N	\N	t
1041	612	63	\N	\N	t
1042	613	63	\N	\N	t
1043	614	63	\N	\N	t
1044	615	63	\N	\N	t
1045	616	63	\N	\N	t
1046	617	63	\N	\N	t
1047	618	63	\N	\N	t
1048	619	63	\N	\N	t
1049	620	63	\N	\N	t
1050	621	63	\N	\N	t
1051	622	63	\N	\N	t
1052	622	98	\N	\N	t
1053	622	99	\N	\N	t
1054	623	63	\N	\N	t
1055	624	63	\N	\N	t
1056	625	63	\N	\N	t
1057	626	63	\N	\N	t
1058	627	63	\N	\N	t
1059	628	63	\N	\N	t
1060	629	63	\N	\N	t
1061	630	63	\N	\N	t
1062	631	63	\N	\N	t
1063	632	63	\N	\N	t
1064	633	63	\N	\N	t
1065	634	63	\N	\N	t
1066	635	63	\N	\N	t
1067	636	63	\N	\N	t
1068	637	63	\N	\N	t
1069	638	63	\N	\N	t
1070	639	63	\N	\N	t
1071	640	66	\N	\N	t
1072	640	67	\N	\N	t
1073	640	68	\N	\N	t
1074	640	69	\N	\N	t
1075	641	3	\N	\N	t
1076	641	4	\N	\N	t
1077	641	5	\N	\N	t
1078	641	6	\N	\N	t
1079	641	10	\N	\N	t
1080	641	18	\N	\N	t
1081	641	23	\N	\N	t
1082	641	63	\N	\N	t
1083	641	75	\N	\N	t
1084	641	76	\N	\N	t
1085	641	84	\N	\N	t
1086	641	98	\N	\N	t
1087	641	99	\N	\N	t
1088	642	77	\N	\N	t
1089	643	78	\N	\N	t
1090	643	102	\N	\N	t
1091	644	10	\N	\N	t
1092	644	18	\N	\N	t
1093	645	2	\N	\N	t
1094	645	3	\N	\N	t
1095	645	4	\N	\N	t
1096	645	5	\N	\N	t
1097	645	6	\N	\N	t
1098	645	10	\N	\N	t
1099	645	76	\N	\N	t
1100	645	98	\N	\N	t
1101	645	99	\N	\N	t
4	2	4	\N	\N	t
1	7	4	\N	DTIC	t
\.


--
-- Name: ctl_dependencia_establecimiento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_dependencia_establecimiento_id_seq', 1, false);


--
-- Name: ctl_dependencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_dependencia_id_seq', 1, true);


--
-- Data for Name: ctl_empleado; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_empleado (id, id_dependencia_establecimiento, id_cargo, nombre, apellido, fecha_nacimiento, correo_electronico) FROM stdin;
1	4	1	Juan	Roque	1985-01-12	roquej@gmail.com
2	4	2	Saul	Aguilar	1991-08-09	saguilar@yahoo.es
3	4	2	Carolina	Perez	1972-06-11	cperez@salud.gob
5	4	2	Oscar	Jimenez	1979-10-21	jimenez.osc979@gmail.com
4	4	2	Leticia	Salamanca	1988-04-12	letty.sal@gmail.com
35	1	1	Emilio	Perla	1972-03-04	epeña@gmail.com
6	1	3	Marcos	Rivera	1986-10-24	mrivera@salud.gob
7	1	3	Mauricio	Castro	1990-03-27	mcastro@salud.gob
8	1	3	Karla	Guerrero	1984-03-03	kguerrero@salud.gob
9	1	3	Jose	ponce	1987-05-04	jponce@gmail.com
10	1	1	Martin	Reyes	1982-01-03	mreyes@gmail.com
11	1	1	Carlos	Gutierrez	1982-06-01	cgutierres@gmail.com
12	1	1	Pablo	Peña	1982-03-06	cpeña@gmail.com
13	19	1	Luis Edgardo	Torres	1967-06-23	luised019_torres@hotmail.com
14	19	1	Karla Maria	Zelaya	1983-01-02	km.zelaya020@gmail.com
15	19	1	Teresa	Amaya	1994-12-18	nami.amaya94@yahoo.com
16	19	2	Cesar Alejandro	Bonilla	1990-03-29	bonilla_cesale@hotmail.com
17	19	2	Mario	Solorzano	1992-09-10	luigi.solorzano@gmail.com
18	19	2	Claudia	Cruz	1989-07-27	ccruz@salud.gob.sv
19	19	2	Carmen	Pineda de Melendez	1956-04-13	cpineda@salud.gob.sv
20	4	2	Carolina	Perez	1972-06-11	cperez@salud.gob.sv
21	19	2	Fatima Lissette	Godoy	1996-11-05	Fatyliss_god@hotmail.com
22	19	2	Geovanny Misael	Carranza Segovia	1976-05-07	gcarranza@saud.gob.sv
23	12	1	Raul Alexander	Menjivar Perez	1959-08-08	raul_menjivar@hotmail.com
24	12	1	Vanessa 	Molina Rodriguez	1974-01-22	rodmolina@yahoo.es
25	12	2	Rene Mauricio 	Santamaria	1983-07-19	santamaria.mau@gmail.com
26	12	2	Daniela Raquel	Mendez Medina	1985-05-09	dany.mendez@gmail.com
27	12	2	Carlos Rafael	Zepeda	1979-01-31	charly.zepeda.yahoo.com
28	12	2	Victor	Gonzalez Zanabria	1989-11-26	victor_gonzalez@hotmail.com
29	659	1	Elena Margarita	Sanchez	1961-04-28	margarita_sanchez@hotmail.com
30	859	1	Juan Miguel	Diaz	1966-03-15	jmiguel.diaz@yahoo.es
31	322	1	Wendy Lourdes	Cañas Escobar	1980-04-22	wescobar@yahoo.com
32	101	1	Manuel de Jesus	Rivera Ruiz	1991-07-17	shus.rruiz@gmail.com
33	19	2	Gerardo Eliseo	Serrano Castro	1984-10-16	madara.serrano@gmail.com
34	12	2	William José	Mejia Batres	1987-08-24	will.mejia@gmail.com
\.



--
-- Name: ctl_empleado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_empleado_id_seq', 36, false);


--
-- Data for Name: ctl_establecimiento; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_establecimiento (id, nombre) FROM stdin;
2	Ministerio de Salud
3	Región Central
4	Región Paracentral
5	Región Oriental
6	Región Metropolitana
7	Región Occidental
8	UNIDAD DE ALMACENES
9	CASA DE SALUD, SAN MARTIN (GUAYMANGO)
10	CENTRO RURAL DE NUTRICION EL ZAPOTE
11	UNIDAD DE SALUD, AHUACHAPAN
12	CASA DE SALUD, SAN BENITO(SN.FCO.MENEND)
13	UNIDAD DE SALUD, APANECA
14	CASA DE SALUD, CANTON RIO FRIO
15	UNIDAD DE SALUD, ATIQUIZAYA
16	CASA DE SALUD, CANTON EL TIGRE (AHUACH.)
17	UNIDAD DE SALUD, CARA SUCIA
18	CASA DE SALUD, CANTON CHAGUITE (TACUBA)
19	UNIDAD DE SALUD, ATACO
20	UNIDAD DE SALUD, TACUBA
21	UNIDAD DE SALUD, LA HACHADURA
22	UNIDAD DE SALUD, GUAYMANGO
23	UNIDAD DE SALUD, JUJUTLA
24	UNIDAD DE SALUD SAN JOSE EL NARANJO (JUJUTLA)
25	UNIDAD DE SALUD, LAS CHINAMAS
26	UNIDAD DE SALUD, GUAYAPA ABAJO
27	UNIDAD DE SALUD, SAN PEDRO PUXTLA
28	UNIDAD DE SALUD, SAN LORENZO(AHUACHAPAN)
29	UNIDAD DE SALUD, TURIN
30	UNIDAD DE SALUD, EL REFUGIO
31	UNIDAD DE SALUD, SAN FRANCISCO MENENDEZ
32	UNIDAD DE SALUD, BARRA DE SANTIAGO
33	UNIDAD DE S.EL ZAPOTE (SAN FCO.MENENDEZ)
34	UNIDAD DE SALUD, GARITA PALMERA
35	UNIDAD DE SALUD, ING.JAVIER ESTRADA
36	HOSPITAL NACIONAL SAN JUAN DE DIOS (SANTA ANA)
37	CENT.RURAL NUTRIC.PRIMAVERA (STA.ANA)
38	UNIDAD DE SALUD,DR.TOMAS PINEDA MARTINEZ
39	HOSPITAL NACIONAL DE METAPAN
40	CENT.R.NUTRIC.SN.CRISTOBAL LA MAGDALENA
41	UNIDAD DE SALUD, CASA DEL NIÑO
42	HOSPITAL NACIONAL DE CHALCHUAPA
43	UNIDAD DE SALUD, EL PALMAR
44	UNIDAD DE SALUD, SANTA LUCIA (SANTA ANA)
45	UNIDAD DE SALUD, SAN MIGUELITO
46	UNIDAD DE SALUD, SAN RAFAEL (SANTA ANA)
47	UNIDAD DE SALUD, SANTA BARBARA
48	CASA DE SALUD, LOS APOYOS
49	UNIDAD DE SALUD, COATEPEQUE
50	CASA DE SALUD, LOS RIVAS
51	UNIDAD DE SALUD, TEXISTEPEQUE
52	CASA DE SALUD, MONTENEGRO
53	UNIDAD DE SALUD, EL CONGO
54	UNIDAD DE S. CANDELARIA DE LA FRONTERA
55	UNIDAD DE SALUD, SANTA ROSA GUACHIPILIN
56	UNIDAD DE SALUD, GUARNECIA
57	UNIDAD DE SALUD, PLANES DE LA LAGUNA
58	UNIDAD DE SALUD, NATIVIDAD
59	UNIDAD DE SALUD, EL PORVENIR
60	CASA DE SALUD, EL PINALON
61	UNIDAD DE SALUD,SAN SEBASTIAN SALITRILLO
62	CASA DE SALUD, CASAS DE TEJA
63	UNIDAD DE S.SAN JUAN LAS MINAS (METAPAN)
64	CASA DE SALUD, LOMAS DE SAN MARCELINO
65	UNIDAD DE SALUD, MASAHUAT
66	CASA DE SALUD, PILETAS (COATEPEQUE)
67	UNIDAD DE SALUD, SAN JERONIMO (METAPAN)
68	UNIDAD DE SALUD, BELEN GUIJATH (METAPAN)
69	UNIDAD DE SALUD, LA PARADA,ALDEA BOLAÑOS
70	UNIDAD DE SALUD, SAN JACINTO(COATEPEQUE)
71	UNIDAD D/SALUD.SAN JOSE INGENIO(METAPAN)
72	UNIDAD DE SALUD, SANTIAGO DE LA FRONTERA
73	UNIDAD DE SALUD, SAN ANTONIO PAJONAL
74	UNIDAD DE SALUD, EL TINTERAL
75	UNIDAD DE SALUD, SAN MIGUEL TEXISTEPEQUE
76	UNIDAD DE SALUD, EL COCO
77	UNIDAD DE SALUD, SABANETAS, EL PASTE
78	CASA DE SALUD, PIEDRAS PACHAS
79	UNIDAD DE SALUD, IZALCO
80	CASA DE SALUD, CANTON CUYAGUALO (IZALCO)
81	CENTRO RURAL DE NUTRICION LAS LAJAS
82	UNIDAD DE SALUD, NAHUIZALCO
83	CASA DE SALUD, CANTON TALCOMUNCA
84	CENTRO RURAL DE NUTRICION LOS LAGARTOS
85	UNIDAD DE SALUD, ARMENIA
86	CASA DE SALUD, CANTON SAN LUCAS
87	CENTRO RURAL NUTRICION SAN JUAN DE DIOS
88	UNIDAD DE SALUD, SAN JULIAN
89	CASA DE SALUD, CTON.SAN JUAN DE DIOS
90	UNIDAD DE SALUD, ACAJUTLA
91	UNIDAD DE SALUD DR.FRANCISCO MAGAÑA H.JUAYUA
92	UNIDAD DE SALUD DR.LEONARDO A.LOPEZ VIGIL(SONZACATE)
93	UNIDAD DE SALUD, METALIO (ACAJUTLA)
94	UNIDAD DE SALUD, LA MAJADA (JUAYUA)
95	UNIDAD DE SALUD, NAHULINGO
96	UNIDAD DE SALUD, SANTA CATARINA MASAHUAT
97	UNIDAD DE SALUD, SANTO DOMINGO DE GUZMAN
98	UNIDAD DE SALUD, SANTA ISABEL ISHUATAN
100	UNIDAD DE SALUD, SALINAS DE AYACACHAPA
101	UNIDAD DE SALUD, CALUCO
102	UNIDAD DE SALUD, CUISNAHUAT
103	UNIDAD DE SALUD, SAN ANTONIO DEL MONTE
104	UNIDAD DE SALUD, SALCOATITAN
105	UNIDAD DE SALUD, LOS ARENALES (NAHUIZAL)
106	CASA DE SALUD SAN VICENTE (CAND.LA FRONTERA)
107	CASA DE SALUD, LLANO GRANDE
108	HOSPITAL NACIONAL DR. LUIS EDMUNDO VASQUEZ (CHALATENANGO)
109	CENTRO RURAL DE NUTRICION SANTA BARBARA
110	UNIDAD DE SALUD, LA PALMA
111	CASA DE SALUD, GUACHIPILIN
112	HOSPITAL NACIONAL DE NUEVA CONCEPCION
113	CENTRO RURAL DE NUTRICION LOS CRUCES
114	UNIDAD DE SALUD, SAN JOSE LAS FLORES
115	CASA DE SALUD, TEOSINTE
116	CENTRO RURAL DE NUTRIC. AGUAJE ESCONDIDO
117	UNIDAD DE SALUD,EL DORADO (CHALATENANGO)
118	CASA DE SALUD, EL TIGRE
119	CENTRO RURAL DE NUTRICION LOS DERAS
120	UNIDAD DE SALUD, DULCE NOMBRE DE MARIA
121	CASA DE SALUD, EL PEPETO
122	UNIDAD DE SALUD, NOMBRE DE JESUS
123	CASA DE SALUD, EL EMCUMBRADO
124	CENTRO RURAL DE NUTRICION LAS FLORES
125	UNIDAD DE SALUD, AGUA CALIENTE
126	CASA DE SALUD, CERRO GRANDE
127	CENTRO RURAL DE NUTRICION SAN ANTONIO
128	UNIDAD DE SALUD, TEJUTLA
129	CASA DE SALUD, OBRAJUELO
130	CENTRO RURAL DE NUTRICION LA CRUZ
131	UNIDAD DE SALUD, LA REINA
132	CASA DE SALUD, LOS PLANES
133	CENTRO RURAL DE NUTRICION EL CHUPADERO
134	UNIDAD DE SALUD, EL PARAISO
135	CASA DE SALUD, SUNAPA
136	CENTRO RURAL DE NUTRICION, JUANCORA
137	UNIDAD DE SALUD, ARCATAO
138	UNIDAD DE SALUD, LAS VUELTAS
139	UNIDAD DE SALUD, LA LAGUNA
140	UNIDAD DE SALUD, CITALA
141	UNIDAD DE SALUD, SAN IGNACIO
142	UNIDAD DE SALUD, SAN FRANCISCO MORAZAN
143	UNIDAD DE SALUD, COMALAPA
144	UNIDAD DE SALUD, OJOS DE AGUA
145	UNIDAD DE SALUD, SAN LUIS DEL CARMEN
146	UNIDAD DE SALUD, SAN ANTONIO LOS RANCHOS
147	UNIDAD DE SALUD, POTONICO
148	UNIDAD DE SALUD, AZACUALPA
149	UNIDAD DE SALUD, SAN FERNANDO (CHALATEN)
150	UNIDAD DE SALUD, SAN FRANCISCO LEMPA
151	UNIDAD DE SALUD, NUEVA TRINIDAD
152	UNIDAD DE SALUD, SAN RAFAEL
153	UNIDAD DE SALUD,CONCEPCION QUEZALTEPEQUE
154	UNIDAD DE SALUD, EL CARRIZAL
155	UNIDAD DE SALUD, SANTA RITA
156	UNIDAD DE SALUD, SAN ANTONIO DE LA CRUZ
157	UNIDAD DE SALUD, SAN MIGUEL DE MERCEDES
158	UNIDAD DE SALUD, SAN JOSE CANCASQUE
159	UNIDAD DE SALUD, POTRERO SULA
160	UNIDAD DE SALUD, ARRACAOS
161	UNIDAD DE SALUD, LAS PILAS
162	UNIDAD DE SALUD, SAN ISIDRO LABRADOR
163	UNIDAD DE SALUD, VAINILLAS
164	CASA DE SALUD, LAS MARGARITAS
165	HOSPITAL NACIONAL SAN RAFAEL (SANTA TECLA)
166	CENTRO RURAL DE NUTRICION SHUTIA
167	UNIDAD DE SALUD, PUERTO LA LIBERTAD
168	CASA DE SALUD, GUADALUPE
169	CENTRO RURAL NUTRICION SAN ARTURO NIZA
170	UNIDAD DE SALUD, DR.FCO.LIMA (JAYAQUE)
171	CASA DE SALUD, LAS GRANADILLAS
172	CENTRO RURAL DE NUTRICION MELARA
173	UNIDAD DE SALUD, CIUDAD ARCE
174	CASA DE SALUD, EL PROGRESO
175	CENTRO RURAL DE NUTRICION EL CONACASTE
176	UNIDAD DE SALUD, QUEZALTEPEQUE
177	CASA DE SALUD, SANTA EDUVIGES
178	CENTRO RURAL DE NUTRICION SITIO GRANDE
179	UNIDAD DE SALUD, SAN JUAN OPICO
180	CENTRO RURAL DE NUTRICION ALVAREZ
181	UNIDAD DE SALUD, SAN PABLO TACACHICO
182	UNIDAD DE SALUD, LOURDES (COLON)
183	UNIDAD DE SALUD, SITIO DEL NIÑO
184	UNIDAD DE S. DR. ALBERTO AGUILAR RIVAS
185	CASA DE SALUD, ING. ORLANDO RECINOS
186	UNIDAD DE SALUD, COMASAGUA
187	CASA DE SALUD, LA ESMERALDITA
188	UNIDAD DE SALUD, SAN MATIAS
189	CASA DE SALUD, LLANO VERDE
190	UNIDAD DE SALUD, TEPECOYO
191	CASA DE SALUD, ALVAREZ
192	UNIDAD DE SALUD, HUIZUCAR
193	UNIDAD DE SALUD, TEOTEPEQUE
194	UNIDAD DE SALUD, SAN JOSE VILLANUEVA
195	UNIDAD DE SALUD, ZARAGOZA
196	UNIDAD DE SALUD, MIZATA
197	UNIDAD DE SALUD, CHILTIUPAN
198	UNIDAD DE SALUD,DR.CARLOS DIAZ DEL PINAL
199	UNIDAD DE SALUD, TAMANIQUE
200	UNIDAD DE SALUD, SACACOYO
201	UNIDAD DE SALUD, JICALAPA
202	UNIDAD DE SALUD, TALNIQUE
203	CASA DE SALUD, CANTON EL ESPINO
204	Hospital Nacional de Suchitoto
205	UNIDAD DE SALUD, SAN PEDRO PERULAPAN
206	CASA DE SALUD, CANTON EL CARMEN
207	HOSPITAL NACIONAL DE COJUTEPEQUE
208	UNIDAD DE SALUD, SAN JOSE GUAYABAL
209	CASA DE SALUD, CANTON TECOLUCO
210	UNIDAD DE SALUD, SAN RAFAEL CEDROS
211	CASA DE SALUD, CANTON TECOMATEPEQUE
212	UNIDAD DE SALUD, SANTA CRUZ MICHAPA
213	CASA DE SALUD, CANTON CORRAL VIEJO
214	UNIDAD DE SALUD, TENANCINGO
215	CASA DE SALUD, CANTON SAN ANTONIO
216	UNIDAD DE SALUD, SANTA CRUZ ANALQUITO
217	CASA DE SALUD, CANTON PIEDRA LABRADA
218	UNIDAD DE SALUD, CANDELARIA CUSCATLAN
219	CASA DE SALUD, CANTON SOLEDAD
220	UNIDAD DE SALUD, ROSARIO CUSCATLAN
221	UNIDAD DE SALUD, MONTE SAN JUAN
222	UNIDAD DE SALUD, ORATORIO DE CONCEPCION
223	UNIDAD DE SALUD, SAN CRISTOBAL
224	UNIDAD DE SALUD, SAN RAMON
640	PROYECTOS DE COOPERACION 
226	UNIDAD DE SALUD, EL CARMEN CUSCATLAN
227	CASA DE SALUD, MERCEDES LA CEIBA
228	HOSPITAL NACIONAL SANTA TERESA (ZACATECOLUCA)
229	CENTRO RURAL DE NUTRICION EL CAUCA
230	UNIDAD DE SALUD, SAN PEDRO NONUALCO
231	CASA DE SALUD, AMATECAMPO
232	CENTRO RURAL DE NUTRICION EL TIHUILOCOYO
233	UNIDAD DE SALUD, SAN PEDRO MAZAHUAT
234	CASA DE SALUD, GUADALUPE LA ZORRA
235	CENTRO RURAL DE NUTRICION HOJA DE SAL
236	UNIDAD DE SALUD, SANTIAGO NONUALCO
237	CASA DE SALUD, LA CALZADA
238	CENTRO RURAL DE NUTRICION EL PORFIADO
239	UNIDAD DE SALUD, SAN LUIS LA HERRADURA
240	CASA DE SALUD, ASTORIA
359	UNIDAD DE SALUD, SANTA MARIA
241	CENTRO RURAL DE NUTRICION SANTA CLARA
242	UNIDAD DE SALUD, SAN LUIS TALPA
243	CASA DE SALUD, BARAHONA
244	CENTRO RURAL DE NUTRICION EL ACHIOTAL
245	UNIDAD DE SALUD, OLOCUILTA
246	CASA DE SALUD, LA ESPERANZA (OLOCUILTA)
247	CENTRO RURAL DE NUTRICION EL PORVENIR
248	UNIDAD DE SALUD, ROSARIO LA PAZ
249	CASA DE SALUD, SAN SEBASTIAN
250	CENTRO RURAL DE NUTRICION ASTORIA
251	UNIDAD DE SALUD, SAN JUAN NONUALCO
252	CENTRO RURAL NUTRICION SAN MARCOS JIBOA
253	UNIDAD DE SALUD, SANTA MARIA OSTUMA
254	CENTRO RURAL DE NUTRICION LA ZORRA
255	UNIDAD DE SALUD, SAN MIGUEL TEPEZONTES
256	UNIDAD DE SALUD, SAN RAFAEL OBRAJUELO
257	UNIDAD DE SALUD, SAN JUAN TALPA
258	UNIDAD DE SALUD, SAN JUAN TEPEZONTES
259	UNIDAD DE SALUD, SAN FRANCISCO CHINAMECA
260	UNIDAD DE SALUD, JERUSALEN
261	UNIDAD DE SALUD, SANTA LUCIA ORCOYO
262	UNIDAD DE SALUD, SAN EMIGDIO
263	UNIDAD DE SALUD, CUYULTITAN
264	UNIDAD DE SALUD, LAS ISLETAS
265	U.DE S.EL ACHIOTAL (SAN PEDRO MAZAGUAT)
266	UNIDAD DE SALUD, SAN JOSE LA PAZ ARRIBA
267	UNIDAD DE SALUD, EL ZAPOTE(LA HERRADURA)
268	UNIDAD DE SALUD, SAN ANTONIO MASAHUATH
269	UNIDAD DE SALUD,EL PIMENTAL (SN.L.TALPA)
270	UNIDAD DE SALUD, TAPALHUACA
271	UNIDAD DE SALUD, PARAISO DE OSORIO
272	UNIDAD DE S.DR.CARLOS ALBERTO GALEANO
273	CASA DE SALUD, SAN FRANCISCO IRAHETA
274	HOSPITALl NACIONAL DE SENSUNTEPEQUE
275	CENTRO RURAL NUTRICION ORATORIO CENTRO
276	CASA DE SALUD, POTRERO
277	HOSPITAL NACIONAL DR. JOSE L. SACA (ILOBASCO)
278	UNIDAD DE SALUD, SAN ISIDRO (CABAÑAS)
279	CASA DE SALUD, HUERTAS
280	CENTRO RURAL DE NUTRICION IZCATAL
281	UNIDAD DE SALUD, VILLA VICTORIA
282	CASA DE SALUD,SN.SEBASTIAN CERRON GRANDE
283	CENTRO RURAL DE NUTRICION HACIENDA VIEJA
284	UNIDAD DE SALUD, CINQUERA
285	CASA DE SALUD, SAN NICOLAS
286	CENTRO RURAL DE NUTRICION PUERTONA
287	UNIDAD DE SALUD, TEJUTEPEQUE
288	CASA DE SALUD, COPINOLAPA
289	CENTRO RURAL DE NUTRICION JOBITOS
290	UNIDAD DE SALUD, JUTIAPA
291	CASA DE SALUD, SAN MARCOS
292	CENTRO RURAL DE NUTRICION SAN NICOLAS
293	UNIDAD DE SALUD, VILLA DOLORES
294	CASA DE SALUD, SAN GREGORIO
295	C.R.N.CAMPAMENTO 2, SAN NICOLAS
296	UNIDAD DE SALUD, SANTA LUCIA (ILOBASCO)
297	CASA DE SALUD, CUYANTEPEQUE
298	UNIDAD DE SALUD, CAROLINA (JUTIAPA )
299	CASA DE SALUD, SAN PEDRO
300	CENTRO RURAL NUT. SANTA CRUZ LA JUNTA
301	UNIDAD DE SALUD, GUACOTECTI
302	CASA DE SALUD, PARATAO
303	UNIDAD DE SALUD, SAN FCO.DEL MONTE
304	CASA DE SALUD, SAN CARLOS
305	UNIDAD DE SALUD, ILOBASCO
306	CASA DE SALUD, EL ZAPOTE (TEJUTEPEQUE)
307	UNIDAD DE SALUD, SENSUNTEPEQUE
308	CASA DE SALUD, SAN JOSE (ILOBASCO)
309	UNIDAD DE SALUD, SANTA MARTA
310	HOSPITAL NACIONAL SANTA GERTRUDIS (SAN VICENTE)
311	CENTRO RURAL DE NUTRICION FENADESAL
312	UNIDAD DE SALUD, SAN SEBASTIAN
313	CENTRO RURAL NUTC.SN.CAYETANO ISTEPEQUE
314	CASA DE SALUD, FENADESAL
315	UNIDAD DE SALUD, GUADALUPE
316	UNIDAD DE SALUD, TECOLUCA
317	CASA DE SALUD, AGUA CALIENTE
318	UNIDAD DE SALUD, APASTEPEQUE
319	CASA DE SALUD, DOS QUEBRADAS
320	UNIDAD DE SALUD, VERAPAZ
321	UNIDAD DE SALUD, SANTO DOMINGO
322	UNIDAD DE SALUD, TEPETITAN
323	UNIDAD DE SALUD, PERIFERICA STA ROSA LIMA
324	UNIDAD DE SALUD, SAN ILDEFONSO
325	UNIDAD DE SALUD, SAN ESTEBAN CATARINA
326	UNIDAD DE SALUD, SAN CARLOS LEMPA
327	UNIDAD DE SALUD, SANTA CLARA
328	UNIDAD DE SALUD, SAN LORENZO(SN.VICENTE)
329	UNIDAD DE SALUD, SAN CAYETANO ISTEPEQUE
330	UNIDAD DE SALUD, SAN NICOLAS LEMPA
331	CASA DE SALUD, LA ESPERANZA (JIQUILISCO)
332	HOSPITAL NACIONAL SAN PEDRO
333	CENTRO RURAL DE NUTRICION HACIENDA NUEVA
334	UNIDAD DE SALUD, BERLIN
335	CASA DE SALUD, CANTON SAMURIA
336	HOSPITAL NACIONAL DR. JORGE ARTURO MENA.
337	CENTRO RURAL DE NUTRICION MONTEFRESCO
338	UNIDAD DE SALUD, JUCUAPA
339	CASA DE SALUD, CANTON EL JUTAL
340	HOSPITAL NACIONAL DE JIQUILISCO
341	CENTRO RURAL DE NUTRICION ISLA MADRE SAL
342	CASA DE SALUD, CTN.LA CRUZ (ESTANZUELA)
343	UNIDAD DE SALUD, ESTANZUELAS
344	CASA DE SALUD, CANTON JOCOMONTIQUE
345	UNIDAD DE S. TIERRA BLANCA (JIQUILISCO)
346	CASA DE SALUD, CANTON CHAPETONES
347	UNIDAD DE SALUD, PUERTO EL TRIUNFO
348	CASA DE SALUD, CTON.SAN JOSE (NVA.GRANAD
349	UNIDAD DE SALUD, SANTA ELENA
350	CASA DE SALUD, CTON.PALOMILLA DE GUALCHO
351	UNIDAD DE SALUD, JUCUARAN
352	CASA DE SALUD,CTON.AZACUALPIA DE GUALCHO
353	UNIDAD DE SALUD, OZATLAN
354	CASA DE SALUD, CANTON LA PEÐA (USULUTAN)
355	UNIDAD DE SALUD, CONCEPCION BATRES
356	UNIDAD DE SALUD, SAN AGUSTIN
357	UNIDAD DE SALUD, ALEGRIA
358	UNIDAD DE SALUD, MERCEDES UMAÑA
360	UNIDAD DE SALUD, EL MOLINO
361	UNIDAD DE SALUD, LAS CHARCAS (SN.BUENA.)
362	UNIDAD DE SALUD, SAN FRANCISCO JAVIER
363	UNIDAD DE SALUD, CALIFORNIA
364	UNIDAD DE SALUD, NUEVA GRANADA
365	UNIDAD DE SALUD, TECAPAN
366	UNIDAD DE SALUD, ISLA DE MENDEZ
367	UNIDAD DE SALUD, VILLA EL TRIUNFO
368	UNIDAD DE SALUD, EL CERRITO
369	UNIDAD DE SALUD, EL QUEBRADO
370	UNIDAD DE SALUD, CORRAL DE MULAS
371	UNIDAD DE SALUD, EREGUAYQUIN
372	UNIDAD DE SALUD, PUERTO PARADA
373	UNIDAD DE SALUD, EL ESPINO
374	UNIDAD DE SALUD, SAN DIONISIO
375	UNIDAD DE SALUD, LA CANOA
376	UNIDAD DE SALUD, SALINAS DE SISIGUAYO
377	UNIDAD DE SALUD, NUEVO AMANECER
378	UNIDAD DE SALUD, SAN BUENA VENTURA
379	UNIDAD DE SALUD, LA CRUZ
380	HOSPITAL NACIONAL SAN JUAN DE DIOS (SAN MIGUEL)
381	CENTRO RURAL DE NUTRICION MAYUCAQUIN
382	UNIDAD DE SALUD, CHINAMECA
383	HOSPITAL NACIONAL MONSEÑOR OSCAR ARNULFO ROMERO(CIUDAD BARRIOS)
384	CENTRO RURAL DE NUTRICION LA LAGUNA
385	CASA DE SALUD, LOS RANCHOS
386	HOSPITAL NACIONAL DE NUEVA GUADALUPE
387	UNIDAD DE SALUD, SESORI
388	CASA DE SALUD, SANTA FIDELIA
389	UNIDAD DE SALUD, SAN RAFAEL ORIENTE
390	CASA DE SALUD, LA MISERICORDIA
391	UNIDAD DE SALUD, EL TRANSITO
392	CASA DE SALUD, ASILO SAN ANTONIO
393	UNIDAD DE SALUD, MONCAGUA
394	UNIDAD DE SALUD, SAN GERARDO
395	CASA DE SALUD, LOS CARRETOS
396	U.D.S.DR.ROBERTO A.CARIAS (LA PRESITA)
397	CASA DE SALUD, CHAMBALA
398	UNIDAD DE SALUD, CHIRILAGUA
399	CASA DE SALUD, SAN PEDRO ARENALES
400	UNIDAD DE SALUD, MILAGRO DE LA PAZ
401	CASA DE SALUD,LA LAGUNA SAN GERARDO
402	UNIDAD DE SALUD, LAS PLACITAS
403	CASA DE SALUD,SAN GERONIMO (SAN GERARDO)
404	UNIDAD DE SALUD, CHAPELTIQUE
405	CASA DE SALUD, SAN ANTONIO (CHINAMECA)
406	UNIDAD DE SALUD, LOLOTIQUE
407	CASA DE SALUD, CRUZ SEGUNDA (CHINAMECA)
408	UNIDAD DE SALUD, EL ZAMORAN
409	CASA DE SALUD, EL TABLON
410	UNIDAD DE SALUD, SAN CARLOS (SAN MIGUEL)
411	CASA DE SALUD, CHILANGUERA
412	UNIDAD DE SALUD, SAN ANTONIO SILVA
413	UNIDAD DE SALUD, EL PLATANAR
414	UNIDAD DE SALUD, SAN LUIS DE LA REINA
415	UNIDAD DE SALUD, SAN JORGE
416	UNIDAD DE SALUD, CAROLINA
417	UNIDAD DE SALUD, NUEVO EDEN DE SAN JUAN
418	UNIDAD DE SALUD, ULUAZAPA
419	UNIDAD DE SALUD, SAN ANTONIO DEL NORTE
420	UNIDAD DE SALUD, COMACARAN
421	UNIDAD DE SALUD, EL CUCO
422	UNIDAD DE SALUD, SAN PEDRO CHIRILAGUA
423	UNIDAD DE SALUD, QUELEPA
424	UNIDAD DE SALUD, PRIMAVERA
425	UNIDAD DE SALUD, EL TECOMATAL
426	UNIDAD DE SALUD, EL NIÑO
427	UNIDAD DE S.TIERRA BLANCA (CHIRILAGUA)
428	UNIDAD DE SALUD, EL JOCOTE DULCE
429	UNIDAD D.S. MARTIN ZALDIVAR COL.CARRILLO
430	UNIDAD DE SALUD, TONGOLONA
431	UNIDAD DE SALUD, MIRAFLORES
432	UNIDAD DE SALUD, LAS MARIAS
433	CASA DE SALUD, EL BARRIAL
434	HOSPITAL NACIONAL DE SAN FRANCISCO GOTERA.
435	CENTRO RURAL DE NUTRICION LA ESTANCIA
436	UNIDAD DE SALUD, JOCORO
437	CASA DE SALUD, QUEBRACHOS
438	UNIDAD DE SALUD, OSICALA
439	CASA DE SALUD, SIMIENTOS (YAMABAL)
440	UNIDAD DE SALUD, EL DIVISADERO
441	CASA DE SALUD, EL PEÑON  (SOCIEDAD)
442	UNIDAD DE SALUD, CORINTO
443	CASA DE SALUD EL CARRIZAL, (SAN SIMON)
444	UNIDAD DE SALUD, PERQUIN
445	CASA DE SALUD, LA JOYA DEL MATAZANO
446	UNIDAD DE SALUD, CACAOPERA
447	UNIDAD DE SALUD, SAN LUIS
448	UNIDAD DE SALUD, SOCIEDAD
449	UNIDAD DE SALUD, GUATAJIAGUA
450	UNIDAD DE SALUD, JOCOITIQUE
451	UNIDAD DE SALUD, TOROLA
452	UNIDAD DE SALUD, MEANGUERA
453	UNIDAD DE SALUD, SAN FERNANDO (MORAZAN)
454	UNIDAD DE SALUD, JOATECA
455	UNIDAD DE SALUD, SAN SIMON
456	UNIDAD DE SALUD, DELICIAS DE CONCEPCION
457	UNIDAD DE SALUD, CHILANGA
458	UNIDAD DE SALUD, YAMABAL (SENSEMBRA)
459	UNIDAD DE SALUD, SAN ISIDRO (MORAZAN)
460	UNIDAD DE SALUD, SAN CARLOS (SAN CARLOS)
461	UNIDAD DE SALUD, VILLA EL ROSARIO
462	UNIDAD DE SALUD, GUALOCOCTI
463	UNIDAD DE SALUD, YOLOAIQUIN
464	UNIDAD DE SALUD, LOLOTIQUILLO
465	UNIDAD DE SALUD, ARAMBALA
466	CASA DE SALUD, PIEDRAS BLANCAS
467	HOSPITAL NACIONAL DE LA UNION
468	CENTRO RURAL DE NUTRICION EL DERRUMBADO
469	U.D.S.ENF.ZOILA E.TURCIOS DE JIMENEZ
470	CASA DE SALUD, LAS TUNAS
471	HOSPITAL NACIONAL DE SANTA ROSA DE LIMA
472	U.D.S.ANA Ma.ALFARO SANCHEZ NUEVA ESPART
473	CASA DE SALUD, EL CACAO
474	UNIDAD DE SALUD, SAN ALEJO
475	CASA DE SALUD, EL COYOLITO (LA UNION)
476	UNIDAD DE SALUD, PASAQUINA
477	CASA DE SALUD, EL RINCON (EL SAUCE)
478	UNIDAD DE SALUD, INTIPUCA
479	CASA DE SALUD, EL GUAJINIQUIL (LISLIQUE)
480	UNIDAD DE SALUD, ANAMOROS
481	CASA DE SALUD, EL BEJUCAL (SOCIEDAD)
482	UNIDAD DE SALUD, EL SAUCE
483	CASA DE SALUD, EL TALPETATE
484	UNIDAD DE SALUD, YUCUAIQUIN
485	CASA DE SALUD, AGUA FRIA (SAN ALEJO)
486	UNIDAD DE SALUD, CONCHAGUA
487	CASA DE SALUD, SAN JERONIMO (SAN ALEJO)
488	UNIDAD DE SALUD, MEANGUERA DEL GOLFO
489	CASA DE SALUD, AGUA BLANCA (ANAMOROS)
490	UNIDAD DE SALUD, CONCEPCION DE ORIENTE
491	UNIDAD DE SALUD, LISLIQUE
492	UNIDAD DE SALUD, EL TAMARINDO
493	UNIDAD DE SALUD, MONTECA
494	UNIDAD DE SALUD, OLOMEGA (EL CARMEN)
495	UNIDAD DE SALUD, BOLIVAR
496	UNIDAD DE SALUD, ISLA ZACATILLO
497	U.DE S.ISLA CONCHAGUITA (MEANG.D/GOLFO)
498	UNIDAD DE SALUD, POLOROS
499	UNIDAD DE SALUD, YAYANTIQUE
500	UNIDAD DE SALUD, EL CARMEN
501	UNIDAD DE SALUD, SAN JOSE DE LA FUENTE
502	UNIDAD DE SALUD, AGUA CALIENTE(LA UNION)
503	UNIDAD DE SALUD, HATO NUEVO (SN. ALEJO)
504	UNIDAD DE SALUD, LLANO LOS PATOS
505	UNIDAD DE SALUD, AGUA ESCONDIDA
506	UNIDAD DE SALUD, BOBADILLA (SAN ALEJO)
507	UNIDAD DE SALUD, SAN FELIPE (PASAQUINA)
508	UNIDAD DE SALUD, EL HUISQUIL (CONCHAGUA)
509	UNIDAD DE SALUD, EL FARO (CONCHAGUA)
510	UNIDAD DE SALUD, EL PICHE
511	CASA DE SALUD, DOS DE MAYO
512	HOSPITAL NACIONAL ROSALES
513	CENTRO RURAL NUTRICION MAPILAPA (NEJAPA)
514	CLINICA DE EMPLEADOS MSPAS
515	CASA DE SALUD, CORO QUIÑONEZ
516	UNIDAD D.S.DR.MAURICIO SOL NERIO SN.JAC.
517	CASA DE SALUD, SAN JERONIMO LOS PLANES
518	HOSPITAL NACIONAL DE NIÑOS BENJAMIN BLOOM
519	C.R.N.CANTON LAS DELICIAS (SAN MARTIN)
520	CASA DE SALUD, CAMPAMENTO MORAZAN
521	U.D.S.DR.JUAN R.ALVARENGA.SN.MIGUELITO
522	CASA DE SALUD, MALACOFF
523	HOSPITAL DE MATERNIDAD DR. ARGUELLO ESCOLAN
524	CENTRO RURAL DE NUT.EL CEDRO(PANCHIMALCO)
525	CASA DE SALUD, FINCA SERPA (S.S.)
526	UNIDAD DE SALUD, CONCEPCION
527	CASA DE SALUD, EL TRANSITO
528	HOSPITAL NACIONAL PSIQUIATRICO DR. JOSE MOLINA MARTINEZ
529	C.R.NUTRIC. COMUNIDAD VALLE LAS DELICIAS
530	CASA DE SALUD, SANTA ROSA ATLACATL (C.D)
531	UNIDAD DE SALUD, BARRIOS
532	CASA DE SALUD, TUTULTEPEQUE
533	HOSPITAL NACIONAL DE NEUMOLOGIA DR. JOSE ANTONIO SALDAÑA.
534	CASA DE SALUD, LAS PALMAS
535	UNIDAD DE SALUD, MONSERRAT
536	HOSPITAL NACIONAL DR. JUAN JOSE FERNANDEZ (ZACAMIL)
537	UNIDAD DE SALUD, LOURDES (SAN SALVADOR)
538	CASA DE SALUD, LOS RAMIREZ
539	HOSPITAL NACIONAL ENFERMERA ANGELICA VIDAL DE NAJARRO (SAN BARTOLO)
540	CASA DE SALUD, PAPINI
541	UNIDAD DE SALUD, ZACAMIL
542	CASA DE SALUD, SAN ANTONIO SEGURA
543	CASA DE SALUD, LA FORTALEZA
544	U.DE S.DR.HUGO MORAN QUIJADA, MEJICANOS
545	CASA DE SALUD, BRISAS DE CANDELARIA
546	UNIDAD DE SALUD, CUSCATANCINGO
547	CASA DE SALUD, SAN ANTONIO GRANDE
548	UNIDAD DE SALUD, CIUDAD DELGADO
549	CASA DE SALUD, SAN DIEGO
550	U.D.S.ZOILA MARINA DE GUADRON SOYAPANGO
551	CASA DE SALUD, LA JOYA
552	UNIDAD DE SALUD, SANTA LUCIA (ILOPANGO)
553	CASA DE SALUD, LA CABAÑA
554	UNIDAD DE SALUD, SAN MARTIN
555	CASA DE SALUD, GARCITAS
556	UNIDAD DE SALUD, APOPA
557	CASA DE SALUD, EL TRAPICHITO
558	UNIDAD D.S.DR.ROBERTO CACERES B(SN.MARC)
559	CASA DE SALUD, CABAÑAS
560	UNIDAD DE S.DR.JOSE E.AVALOS LA GUARDIA
561	CASA DE SALUD, ALTOS DE JARDINES
562	UNIDAD DE SALUD, NEJAPA
563	CASA DE SALUD, JOYA GRANDE
564	UNIDAD DE SALUD, GUAZAPA
565	CASA DE SALUD, EL CEDRO
566	UNIDAD DE SALUD, AGUILARES
567	CASA DE SALUD, EL AMAYON
568	UNIDAD DE SALUD, TONACATEPEQUE
569	CASA DE SALUD, LAS BARROSAS
570	UNIDAD DE SALUD, SANTIAGO TEXACUANGO
571	CASA DE SALUD, PALO GRANDE
572	UNIDAD DE SALUD, AMATEPEC
573	CASA DE SALUD, PLAN DEL MANGO
574	UNIDAD DE SALUD, SAN ANTONIO ABAD
575	CASA DE SALUD, COLIMA
576	UNIDAD DE SALUD, PANCHIMALCO
577	CASA DE SALUD, SAN ISIDRO
578	UNIDAD DE SALUD, EL PAISNAL
579	CASA DE SALUD, CANTON EL SAUCE
580	UNIDAD DE SALUD, ROSARIO DE MORA
581	CASA DE SALUD, EL CARMEN (SAN SALVADOR)
582	UNIDAD DE SALUD, SAN BARTOLOME PERULAPIA
583	UNIDAD DE SALUD, UNICENTRO
584	UNIDAD DE SALUD, HABITAT CONFIEN
585	UNIDAD DE SALUD, DISTRITO ITALIA
586	UNIDAD DE SALUD, POPOTLAN
587	UNIDAD DE SALUD, CHINTUC
588	UNIDAD DE SALUD, VILLA MARIONA
589	UNIDAD DE SALUD, ANTIGUO CUSCATLAN
590	UNIDAD DE SALUD, NUEVO CUSCATLAN
591	CASA DE SALUD, SAN ANTONIO CHAVEZ
592	CASA DE SALUD, CANDELARIA (COMALAPA)
593	UNIDAD DE SALUD, CHALCHUAPA
594	CASA DE SALUD, LA CEIBITA (CAROLINA)
595	CASA DE SALUD, CONCEPCION COROZAL
596	UNIDAD DE SALUD, PLANES DE RENDEROS
597	CASA DE SALUD, CANTON COPINOL
598	CASA DE SALUD, CANTON CANDELARIA
599	CASA DE SALUD, ZAPOTITAN
600	CASA DE SALUD, EL PICHICHE (ZACATECOLUCA)
601	CASA DE SALUD, SAN BARTOLO (GUATAJIAGUA)
602	CASA DE SALUD, LA JOYA (SOCIEDAD)
603	CASA DE SALUD, HONDABLE (CORINTO)
604	CASA DE SALUD, CHAPARRAL (CHILANGA)
605	CASA DE SALUD, CALAVERA  (CACAOPERA)
606	UNIDAD DE SALUD, ING. ORLANDO RECINOS
607	UNIDAD DE SALUD, SAN JOSE LOS SITIOS
608	UNIDAD DE SALUD,DR.MANUEL GALLADRO,COLON
609	CASA DE SALUD, SAN JOSE LOS SITOS
610	CASA D.S.LAS GRANADILLAS(SITIO DEL NIÑO)
611	CASA DE SALUD, LARA (NUEVA SAN SALVADOR)
612	CASA DE SALUD, SAN ARTURO NIZA
613	CASA DE SALUD, SAN MATIAS
614	CASA DE SALUD, LOS HATILLOS (YUCUAIQUIN)
615	CASA DE SALUD, INTERCOMUNAL (SANTO TOMAS)
616	CASA DE SALUD, CANTON SOLEDAD
617	CASA DE SALUD, CANTON LA BERMUDA
618	CASA DE SALUD, AZACUALPA
619	UNIDAD DE SALUD, MERCEDES LA CEIBA
620	CASA DE SALUD, SAN CARLOS BORROMEO
621	CENTRO RURAL DE NUTRICION EL CIPRES
622	U.D.S. ANEXO HOSPITAL SAN JUAN DIOS S.M.
623	SIBASI CENTRO
624	SIBASI SUR
625	SIBASI ORIENTE
626	SIBASI NORTE
627	SIBASI LA LIBERTAD
628	SIBASI CHALATENANGO
629	SIBASI SAN VICENTE
630	SIBASI LA PAZ
631	SIBASI CUSCATLAN
632	SIBASI CABAÑAS
633	SIBASI SAN MIGUEL
634	SIBASI USULUTAN
635	SIBASI LA UNION
636	SIBASI MORAZAN
637	SIBASI SANTA ANA
638	SIBASI AHUACHAPAN
639	SIBASI SONSONATE
641	HOSPITAL NACIONAL DR. JORGE MAZZINI (SONSONATE)
642	ENTIDADES EXTERNAS
643	ENTIDADES GUBERNAMENTALES
644	FONDO SOLIDARIO PARA LA SALUD(FOSALUD)
645	HOSPITAL NACIONAL FRANCISCO MENENDEZ
1	Establecimiento general
\.


--
-- Name: ctl_establecimiento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_establecimiento_id_seq', 1, false);


--
-- Data for Name: ctl_tipo_dependencia; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_tipo_dependencia (id, nombre) FROM stdin;
1	Interna
2	Entidad Gubernamental
3	Unidad de Salud
4	Casa de Salud
5	Hospital Nacional
6	Centro Rural
7	Clinica
8	SIBASI
\.


--
-- Name: ctl_tipo_dependencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_tipo_dependencia_id_seq', 1, false);


--
-- Data for Name: fos_user_group; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY fos_user_group (id, name, roles) FROM stdin;
2	tecnico	a:1:{i:0;s:10:"ROLE_ADMIN";}
1	solicitante	a:11:{i:0;s:10:"ROLE_ADMIN";i:1;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_LIST";i:2;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_VIEW";i:3;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_EDIT";i:4;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_LIST";i:5;s:60:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_CREATE";i:6;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_VIEW";i:7;s:60:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_DELETE";i:8;s:60:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_EXPORT";i:9;s:62:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_OPERATOR";i:10;s:60:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_MASTER";}
3	Responsable de dependencia	a:71:{i:0;s:10:"ROLE_ADMIN";i:1;s:42:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_EDIT";i:2;s:42:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_LIST";i:3;s:44:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_CREATE";i:4;s:42:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_VIEW";i:5;s:44:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_DELETE";i:6;s:44:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_EXPORT";i:7;s:46:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_OPERATOR";i:8;s:44:"ROLE_MINSAL_SIFDASIFDA_ADMIN_CATALOGO_MASTER";i:9;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_EDIT";i:10;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_LIST";i:11;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_CREATE";i:12;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_VIEW";i:13;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_DELETE";i:14;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_EXPORT";i:15;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_OPERATOR";i:16;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_SERVICIO_MASTER";i:17;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_EDIT";i:18;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_LIST";i:19;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_CREATE";i:20;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_VIEW";i:21;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_DELETE";i:22;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_EXPORT";i:23;s:59:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_OPERATOR";i:24;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RUTA_CICLO_VIDA_MASTER";i:25;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_LIST";i:26;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_SOLICITUD_SERVICIO_VIEW";i:27;s:63:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_EDIT";i:28;s:63:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_LIST";i:29;s:65:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_CREATE";i:30;s:63:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_VIEW";i:31;s:65:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_DELETE";i:32;s:65:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_EXPORT";i:33;s:67:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_OPERATOR";i:34;s:65:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_REPROGRAMACION_SERVICIO_MASTER";i:35;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_EDIT";i:36;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_LIST";i:37;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_CREATE";i:38;s:53:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_VIEW";i:39;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_DELETE";i:40;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_EXPORT";i:41;s:57:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_OPERATOR";i:42;s:55:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_ORDEN_TRABAJO_MASTER";i:43;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_EDIT";i:44;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_LIST";i:45;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_CREATE";i:46;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_VIEW";i:47;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_DELETE";i:48;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_EXPORT";i:49;s:58:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_OPERATOR";i:50;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_EQUIPO_TRABAJO_MASTER";i:51;s:61:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_INFORME_ORDEN_TRABAJO_LIST";i:52;s:61:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_INFORME_ORDEN_TRABAJO_VIEW";i:53;s:52:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_EDIT";i:54;s:52:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_LIST";i:55;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_CREATE";i:56;s:52:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_VIEW";i:57;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DELETE";i:58;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_EXPORT";i:59;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_OPERATOR";i:60;s:54:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_MASTER";i:61;s:64:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_EDIT";i:62;s:64:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_LIST";i:63;s:66:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_CREATE";i:64;s:64:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_VIEW";i:65;s:66:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_DELETE";i:66;s:66:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_EXPORT";i:67;s:68:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_OPERATOR";i:68;s:66:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_TIPO_RECURSO_DEPENDENCIA_MASTER";i:69;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RECURSO_SERVICIO_LIST";i:70;s:56:"ROLE_MINSAL_SIFDASIFDA_ADMIN_SIFDA_RECURSO_SERVICIO_VIEW";}
\.


--
-- Name: fos_user_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('fos_user_group_id_seq', 3, true);


--
-- Data for Name: fos_user_user; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY fos_user_user (id, id_dependencia_establecimiento, id_empleado, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at, created_at, updated_at, date_of_birth, firstname, lastname, website, biography, gender, locale, timezone, phone, facebook_uid, facebook_name, facebook_data, twitter_uid, twitter_name, twitter_data, gplus_uid, gplus_name, gplus_data, token, two_step_code) FROM stdin;
3	\N	\N	sviana	sviana	sviana@salud.gob.sv	sviana@salud.gob.sv	t	3ik1vjp3h9mo0g4cooos8o00swk808g	a2uCQBSR/lqrmIdgi8+HkvtrklbsY0svXGPp7WkyEVom4MWvr/nNcA/fh4NJKc7LZIKwduDUWsABL+xsiv9RCA==	2015-01-29 11:27:07	f	f	\N	\N	\N	a:0:{}	f	\N	2015-01-29 09:37:24	2015-01-29 11:27:07	\N	Sonia	Viana	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
1	4	\N	Minsal	minsal	minsal@salud.gob.sv	minsal@salud.gob.sv	t	nqc2pq64y9wkwk4o0o8o8ossc00skoc	RYzSYKZJbFvpqIUS7AEyN1vQmix9IKNZoIMUmA3jvZQeEVDlECevcOYlCELWoRingMT36/vAtfBmr9rLEsQXaQ==	2015-02-07 02:20:08	f	f	\N	\N	\N	a:2:{i:0;s:16:"ROLE_SUPER_ADMIN";i:1;s:10:"ROLE_ADMIN";}	f	\N	2015-01-27 21:03:12	2015-01-29 11:45:48	\N	Pedro	Perez	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
2	\N	\N	anthony	anthony	anthony.huezo@gmail.com	anthony.huezo@gmail.com	t	44n26usz7740oswcgggg0kk400w8sgc	6sTUfgUKmPOKq0A+UXVHOOAlilTBvx+r6SCWHFgscRbRmn9TdnTCetnNbklRmTNIOBp8r5PVqFC9QVY66tDHYw==	2015-02-07 02:20:54	f	f	\N	\N	\N	a:0:{}	f	\N	2015-01-28 23:40:51	2015-01-29 12:51:53	\N	Anthony	Huezo	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
\.


--
-- Data for Name: fos_user_user_group; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY fos_user_user_group (user_id, group_id) FROM stdin;
1	1
1	3
2	1
1	2
3	3
\.


--
-- Name: fos_user_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('fos_user_user_id_seq', 3, true);


--
-- Data for Name: sidpla_actividad; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sidpla_actividad (id, id_linea_estrategica, id_empleado, descripcion, codigo, activo, meta_anual, descripcion_meta_anual, indicador, medio_verificacion, generado) FROM stdin;
\.


--
-- Name: sidpla_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sidpla_actividad_id_seq', 1, false);


--
-- Data for Name: sidpla_linea_estrategica; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sidpla_linea_estrategica (id, id_dependencia_establecimiento, descripcion, codigo, activo, anio, recurrente) FROM stdin;
\.


--
-- Name: sidpla_linea_estrategica_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sidpla_linea_estrategica_id_seq', 1, false);


--
-- Data for Name: sidpla_subactividad; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sidpla_subactividad (id, id_actividad, id_empleado, descripcion, codigo, activo, meta_anual, descripcion_meta_anual, indicador, medio_verificacion) FROM stdin;
\.


--
-- Name: sidpla_subactividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sidpla_subactividad_id_seq', 1, false);


--
-- Data for Name: sifda_detalle_solicitud_orden; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_detalle_solicitud_orden (id, id_detalle_solicitud_servicio, id_orden_trabajo) FROM stdin;
\.


--
-- Name: sifda_detalle_solicitud_orden_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_detalle_solicitud_orden_id_seq', 1, false);


--
-- Data for Name: sifda_detalle_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_detalle_solicitud_servicio (id, id_solicitud_servicio, descripcion, cantidad_solicitada, cantidad_aprobada, justificacion) FROM stdin;
\.


--
-- Name: sifda_detalle_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_detalle_solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: sifda_equipo_trabajo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_equipo_trabajo (id, id_orden_trabajo, id_empleado, responsable_equipo) FROM stdin;
\.


--
-- Name: sifda_equipo_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_equipo_trabajo_id_seq', 2, true);


--
-- Data for Name: sifda_informe_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_informe_orden_trabajo (id, id_empleado, id_orden_trabajo, id_subactividad, id_dependencia_establecimiento, id_etapa, detalle, fecha_realizacion, fecha_registro, terminado) FROM stdin;
\.


--
-- Name: sifda_informe_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_informe_orden_trabajo_id_seq', 1, true);


--
-- Data for Name: sifda_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_orden_trabajo (id, id_solicitud_servicio, id_estado, id_etapa, id_dependencia_establecimiento, id_prioridad, descripcion, codigo, fecha_creacion, fecha_finalizacion, observacion) FROM stdin;
\.


--
-- Name: sifda_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_orden_trabajo_id_seq', 4, true);


--
-- Data for Name: sifda_recurso_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_recurso_servicio (id, id_informe_orden_trabajo, id_tipo_recurso_dependencia, cantidad, costo_total) FROM stdin;
\.


--
-- Name: sifda_recurso_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_recurso_servicio_id_seq', 1, false);


--
-- Data for Name: sifda_reprogramacion_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_reprogramacion_servicio (id, id_solicitud_servicio, fecha_reprogramacion, justificacion) FROM stdin;
\.


--
-- Name: sifda_reprogramacion_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_reprogramacion_servicio_id_seq', 2, true);


--
-- Data for Name: sifda_retraso_actividad; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_retraso_actividad (id, id_orden_trabajo, razon_retraso) FROM stdin;
\.


--
-- Name: sifda_retraso_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_retraso_actividad_id_seq', 1, false);


--
-- Data for Name: sifda_ruta; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_ruta (id, id_tipo_servicio, descripcion, tipo) FROM stdin;
\.


--
-- Data for Name: sifda_ruta_ciclo_vida; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_ruta_ciclo_vida (id, id_tipo_servicio, id_etapa, descripcion, referencia, jerarquia, ignorar_sig, peso) FROM stdin;
\.


--
-- Name: sifda_ruta_ciclo_vida_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_ruta_ciclo_vida_id_seq', 3, true);


--
-- Name: sifda_ruta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_ruta_id_seq', 1, true);


--
-- Data for Name: sifda_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_solicitud_servicio (id, id_tipo_servicio, user_id, id_dependencia_establecimiento, id_estado, id_medio_solicita, descripcion, fecha_recepcion, fecha_requiere) FROM stdin;
\.


--
-- Name: sifda_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_solicitud_servicio_id_seq', 3, true);


--
-- Data for Name: sifda_tipo_recurso; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tipo_recurso (id, nombre, descripcion, rrhh) FROM stdin;
\.


--
-- Data for Name: sifda_tipo_recurso_dependencia; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tipo_recurso_dependencia (id, id_tipo_recurso, id_dependencia_establecimiento, costo_unitario) FROM stdin;
\.


--
-- Name: sifda_tipo_recurso_dependencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tipo_recurso_dependencia_id_seq', 1, false);


--
-- Name: sifda_tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tipo_recurso_id_seq', 1, false);


--
-- Data for Name: sifda_tipo_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tipo_servicio (id, id_actividad, nombre, descripcion, activo, id_dependencia_establecimiento) FROM stdin;

\.


--
-- Name: sifda_tipo_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tipo_servicio_id_seq', 3, true);


--
-- Data for Name: sifda_tracking_estado; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tracking_estado (id, id_orden_trabajo, id_estado, id_etapa, fecha_inicio, fecha_fin, prog_actividad, observacion) FROM stdin;
\.


--
-- Name: sifda_tracking_estado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tracking_estado_id_seq', 1, false);


--
-- Name: fos_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY fos_user_group
    ADD CONSTRAINT fos_user_group_pkey PRIMARY KEY (id);


--
-- Name: fos_user_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY fos_user_user_group
    ADD CONSTRAINT fos_user_user_group_pkey PRIMARY KEY (user_id, group_id);


--
-- Name: fos_user_user_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY fos_user_user
    ADD CONSTRAINT fos_user_user_pkey PRIMARY KEY (id);


--
-- Name: pk_bitacora; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT pk_bitacora PRIMARY KEY (id);


--
-- Name: pk_catalogo; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY catalogo
    ADD CONSTRAINT pk_catalogo PRIMARY KEY (id);


--
-- Name: pk_catalogo_detalle; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY catalogo_detalle
    ADD CONSTRAINT pk_catalogo_detalle PRIMARY KEY (id);


--
-- Name: pk_ctl_cargo; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_cargo
    ADD CONSTRAINT pk_ctl_cargo PRIMARY KEY (id);


--
-- Name: pk_ctl_dependencia; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_dependencia
    ADD CONSTRAINT pk_ctl_dependencia PRIMARY KEY (id);


--
-- Name: pk_ctl_empleado; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT pk_ctl_empleado PRIMARY KEY (id);


--
-- Name: pk_ctl_establecimiento; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_establecimiento
    ADD CONSTRAINT pk_ctl_establecimiento PRIMARY KEY (id);


--
-- Name: pk_ctl_tipo_dependencia; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_tipo_dependencia
    ADD CONSTRAINT pk_ctl_tipo_dependencia PRIMARY KEY (id);


--
-- Name: pk_dependencia_establecimiento; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT pk_dependencia_establecimiento PRIMARY KEY (id);


--
-- Name: pk_detalle; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT pk_detalle PRIMARY KEY (id);


--
-- Name: pk_detalle_solicitud_servicio; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_detalle_solicitud_servicio
    ADD CONSTRAINT pk_detalle_solicitud_servicio PRIMARY KEY (id);


--
-- Name: pk_reprogramacion_servicio; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_reprogramacion_servicio
    ADD CONSTRAINT pk_reprogramacion_servicio PRIMARY KEY (id);


--
-- Name: pk_sidpla_actividad; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT pk_sidpla_actividad PRIMARY KEY (id);


--
-- Name: pk_sidpla_linea_estrategica; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_linea_estrategica
    ADD CONSTRAINT pk_sidpla_linea_estrategica PRIMARY KEY (id);


--
-- Name: pk_sidpla_subactividad; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT pk_sidpla_subactividad PRIMARY KEY (id);


--
-- Name: pk_sifda_equipo_trabajo; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT pk_sifda_equipo_trabajo PRIMARY KEY (id);


--
-- Name: pk_sifda_informe_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT pk_sifda_informe_orden_trabajo PRIMARY KEY (id);


--
-- Name: pk_sifda_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT pk_sifda_orden_trabajo PRIMARY KEY (id);


--
-- Name: pk_sifda_recurso_servicio; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT pk_sifda_recurso_servicio PRIMARY KEY (id);


--
-- Name: pk_sifda_retraso_actividad; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_retraso_actividad
    ADD CONSTRAINT pk_sifda_retraso_actividad PRIMARY KEY (id);


--
-- Name: pk_sifda_ruta; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_ruta
    ADD CONSTRAINT pk_sifda_ruta PRIMARY KEY (id);


--
-- Name: pk_sifda_ruta_ciclo_vida; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT pk_sifda_ruta_ciclo_vida PRIMARY KEY (id);


--
-- Name: pk_sifda_solicitud_servicio; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT pk_sifda_solicitud_servicio PRIMARY KEY (id);


--
-- Name: pk_sifda_tipo_recurso; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_recurso
    ADD CONSTRAINT pk_sifda_tipo_recurso PRIMARY KEY (id);


--
-- Name: pk_sifda_tipo_servicio; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT pk_sifda_tipo_servicio PRIMARY KEY (id);


--
-- Name: pk_sifda_tracking_estado; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT pk_sifda_tracking_estado PRIMARY KEY (id);


--
-- Name: pk_tipo_recurso_dependencia; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT pk_tipo_recurso_dependencia PRIMARY KEY (id);


--
-- Name: almacena_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX almacena_fk ON sifda_tracking_estado USING btree (id_estado);


--
-- Name: atiende_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX atiende_fk ON sifda_orden_trabajo USING btree (id_dependencia_establecimiento);


--
-- Name: conformado_por_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX conformado_por_fk ON ctl_dependencia USING btree (id_tipo_dependencia);


--
-- Name: constituye_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX constituye_fk ON sifda_tipo_servicio USING btree (id_actividad);


--
-- Name: crea_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX crea_fk ON sifda_orden_trabajo USING btree (id_solicitud_servicio);


--
-- Name: define_estado_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_estado_fk ON sifda_orden_trabajo USING btree (id_estado);


--
-- Name: define_etapa_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_etapa_fk ON sifda_orden_trabajo USING btree (id_etapa);


--
-- Name: define_evento_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_evento_fk ON bitacora USING btree (id_evento);


--
-- Name: define_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_fk ON sifda_solicitud_servicio USING btree (id_tipo_servicio);


--
-- Name: define_medio_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_medio_fk ON sifda_solicitud_servicio USING btree (id_medio_solicita);


--
-- Name: define_prioridad_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_prioridad_fk ON sifda_orden_trabajo USING btree (id_prioridad);


--
-- Name: define_recurso_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_recurso_fk ON sifda_recurso_servicio USING btree (id_tipo_recurso_dependencia);


--
-- Name: describe_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX describe_fk ON sifda_detalle_solicitud_servicio USING btree (id_solicitud_servicio);


--
-- Name: ejecuta_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX ejecuta_fk ON sidpla_actividad USING btree (id_empleado);


--
-- Name: es_atendida_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX es_atendida_fk ON sifda_equipo_trabajo USING btree (id_orden_trabajo);


--
-- Name: es_conformado_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX es_conformado_fk ON sidpla_subactividad USING btree (id_actividad);


--
-- Name: es_generado_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX es_generado_fk ON sifda_informe_orden_trabajo USING btree (id_orden_trabajo);


--
-- Name: especifica_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX especifica_fk ON catalogo_detalle USING btree (id_catalogo);


--
-- Name: establece_estado_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX establece_estado_fk ON sifda_solicitud_servicio USING btree (id_estado);


--
-- Name: forma_parte_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX forma_parte_fk ON sifda_equipo_trabajo USING btree (id_empleado);


--
-- Name: genera_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX genera_fk ON bitacora USING btree (user_id);


--
-- Name: gestiona_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX gestiona_fk ON sidpla_subactividad USING btree (id_empleado);


--
-- Name: idx_b3c77447a76ed395; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b3c77447a76ed395 ON fos_user_user_group USING btree (user_id);


--
-- Name: idx_b3c77447fe54d947; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b3c77447fe54d947 ON fos_user_user_group USING btree (group_id);


--
-- Name: idx_bitacora; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_bitacora ON bitacora USING btree (id);


--
-- Name: idx_catalogo_detalle; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_catalogo_detalle ON catalogo_detalle USING btree (id);


--
-- Name: idx_ctl_cargo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_cargo ON ctl_cargo USING btree (id);


--
-- Name: idx_ctl_dependencia; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_dependencia ON ctl_dependencia USING btree (id);


--
-- Name: idx_ctl_establecimiento; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_establecimiento ON ctl_establecimiento USING btree (id);


--
-- Name: idx_ctl_tipo_dependencia; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_tipo_dependencia ON ctl_tipo_dependencia USING btree (id);


--
-- Name: idx_depen_estab; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_depen_estab ON ctl_empleado USING btree (id_dependencia_establecimiento);


--
-- Name: idx_det_orden_trabajo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_det_orden_trabajo ON sifda_detalle_solicitud_orden USING btree (id_orden_trabajo);


--
-- Name: idx_det_solic_serv; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_det_solic_serv ON sifda_detalle_solicitud_orden USING btree (id);


--
-- Name: idx_detalle_solicitud_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_detalle_solicitud_servicio ON sifda_detalle_solicitud_servicio USING btree (id);


--
-- Name: idx_empleado; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_empleado ON ctl_empleado USING btree (id);


--
-- Name: idx_id_cargo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_cargo ON ctl_empleado USING btree (id_cargo);


--
-- Name: idx_id_catalogo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_catalogo ON catalogo_detalle USING btree (id_catalogo);


--
-- Name: idx_id_dep_establecimiento; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_dep_establecimiento ON ctl_dependencia_establecimiento USING btree (id_establecimiento);


--
-- Name: idx_id_dependencia; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_dependencia ON ctl_dependencia USING btree (id_tipo_dependencia);


--
-- Name: idx_id_dependencia_est; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_dependencia_est ON ctl_dependencia_establecimiento USING btree (id_dependencia);


--
-- Name: idx_id_dependencia_establecimiento; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_id_dependencia_establecimiento ON ctl_dependencia_establecimiento USING btree (id);


--
-- Name: idx_id_dependencia_padre; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_dependencia_padre ON ctl_dependencia_establecimiento USING btree (id_dependencia_padre);


--
-- Name: idx_id_det_sol_serv; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_det_sol_serv ON sifda_detalle_solicitud_orden USING btree (id_detalle_solicitud_servicio);


--
-- Name: idx_id_evento; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_evento ON bitacora USING btree (id_evento);


--
-- Name: idx_id_ruta_etapa; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_id_ruta_etapa ON sifda_ruta_ciclo_vida USING btree (id_etapa);


--
-- Name: idx_reprogramacion_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_reprogramacion_servicio ON sifda_reprogramacion_servicio USING btree (id);


--
-- Name: idx_sidpla_actividad; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sidpla_actividad ON sidpla_actividad USING btree (id);


--
-- Name: idx_sidpla_linea_estrategica; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sidpla_linea_estrategica ON sidpla_linea_estrategica USING btree (id);


--
-- Name: idx_sidpla_subactividad; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sidpla_subactividad ON sidpla_subactividad USING btree (id);


--
-- Name: idx_sifda_informe_orden_trabajo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_informe_orden_trabajo ON sifda_informe_orden_trabajo USING btree (id);


--
-- Name: idx_sifda_orden_trabajo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_orden_trabajo ON sifda_orden_trabajo USING btree (id);


--
-- Name: idx_sifda_recurso_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_recurso_servicio ON sifda_recurso_servicio USING btree (id);


--
-- Name: idx_sifda_retraso_actividad; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_retraso_actividad ON sifda_retraso_actividad USING btree (id);


--
-- Name: idx_sifda_ruta; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_ruta ON sifda_ruta USING btree (id);


--
-- Name: idx_sifda_ruta_ciclo_vida; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_ruta_ciclo_vida ON sifda_ruta_ciclo_vida USING btree (id);


--
-- Name: idx_sifda_solicitud_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_solicitud_servicio ON sifda_solicitud_servicio USING btree (id);


--
-- Name: idx_sifda_tipo_recurso; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_tipo_recurso ON sifda_tipo_recurso USING btree (id);


--
-- Name: idx_sifda_tracking_estado; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_sifda_tracking_estado ON sifda_tracking_estado USING btree (id);


--
-- Name: idx_tipo_recurso_dependencia; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_tipo_recurso_dependencia ON sifda_tipo_recurso_dependencia USING btree (id);


--
-- Name: idx_user_id; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_user_id ON bitacora USING btree (user_id);


--
-- Name: index_catalogo_detalle; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX index_catalogo_detalle ON catalogo_detalle USING btree (id);


--
-- Name: indx_sifda_tipo_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX indx_sifda_tipo_servicio ON sifda_tipo_servicio USING btree (id);


--
-- Name: ingresa_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX ingresa_fk ON sifda_solicitud_servicio USING btree (user_id);


--
-- Name: maneja_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX maneja_fk ON ctl_dependencia_establecimiento USING btree (id_establecimiento);


--
-- Name: pertenece_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX pertenece_fk ON ctl_dependencia_establecimiento USING btree (id_dependencia_padre);


--
-- Name: posee_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX posee_fk ON sidpla_linea_estrategica USING btree (id_dependencia_establecimiento);


--
-- Name: proporciona_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX proporciona_fk ON sifda_detalle_solicitud_orden USING btree (id_orden_trabajo);


--
-- Name: puede_tener_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX puede_tener_fk ON sifda_retraso_actividad USING btree (id_orden_trabajo);


--
-- Name: realiza_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX realiza_fk ON sifda_informe_orden_trabajo USING btree (id_dependencia_establecimiento);


--
-- Name: registra_etapa_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX registra_etapa_fk ON sifda_tracking_estado USING btree (id_etapa);


--
-- Name: requiere_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX requiere_fk ON sifda_informe_orden_trabajo USING btree (id_subactividad);


--
-- Name: se_compone_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_compone_fk ON sifda_tipo_recurso_dependencia USING btree (id_tipo_recurso);


--
-- Name: se_generan_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_generan_fk ON sifda_informe_orden_trabajo USING btree (id_etapa);


--
-- Name: se_identifica_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_identifica_fk ON sidpla_actividad USING btree (id_linea_estrategica);


--
-- Name: se_registra_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_registra_fk ON sifda_tracking_estado USING btree (id_orden_trabajo);


--
-- Name: se_traslada_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_traslada_fk ON sifda_reprogramacion_servicio USING btree (id_solicitud_servicio);


--
-- Name: solicita_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX solicita_fk ON sifda_solicitud_servicio USING btree (id_dependencia_establecimiento);


--
-- Name: tiene_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX tiene_fk ON sifda_ruta USING btree (id_tipo_servicio);


--
-- Name: trabaja_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX trabaja_fk ON ctl_empleado USING btree (id_dependencia_establecimiento);


--
-- Name: uniq_583d1f3e5e237e06; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX uniq_583d1f3e5e237e06 ON fos_user_group USING btree (name);


--
-- Name: uniq_c560d76192fc23a8; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX uniq_c560d76192fc23a8 ON fos_user_user USING btree (username_canonical);


--
-- Name: uniq_c560d761a0d96fbf; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX uniq_c560d761a0d96fbf ON fos_user_user USING btree (email_canonical);


--
-- Name: utiliza_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX utiliza_fk ON sifda_tipo_recurso_dependencia USING btree (id_dependencia_establecimiento);


--
-- Name: valora_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX valora_fk ON sifda_recurso_servicio USING btree (id_informe_orden_trabajo);


--
-- Name: fk_actividad_subactividad; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT fk_actividad_subactividad FOREIGN KEY (id_actividad) REFERENCES sidpla_actividad(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_actividad_tipo_servicio; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT fk_actividad_tipo_servicio FOREIGN KEY (id_actividad) REFERENCES sidpla_actividad(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_b3c77447a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user_group
    ADD CONSTRAINT fk_b3c77447a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user_user(id);


--
-- Name: fk_b3c77447fe54d947; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user_group
    ADD CONSTRAINT fk_b3c77447fe54d947 FOREIGN KEY (group_id) REFERENCES fos_user_group(id);


--
-- Name: fk_cargo_empleado; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT fk_cargo_empleado FOREIGN KEY (id_cargo) REFERENCES ctl_cargo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_catalogo_detalle; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY catalogo_detalle
    ADD CONSTRAINT fk_catalogo_catalogo_detalle FOREIGN KEY (id_catalogo) REFERENCES catalogo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_bitacora; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT fk_catalogo_detalle_bitacora FOREIGN KEY (id_evento) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_orden; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_catalogo_detalle_orden FOREIGN KEY (id_prioridad) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_orden_t; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_catalogo_detalle_orden_t FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_solicitud; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_catalogo_detalle_solicitud FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_solicitud_s; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_catalogo_detalle_solicitud_s FOREIGN KEY (id_medio_solicita) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_catalogo_detalle_tracking; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_catalogo_detalle_tracking FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_dependencia_establecimiento; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_dependencia_dependencia_establecimiento FOREIGN KEY (id_dependencia) REFERENCES ctl_dependencia(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_estab_linea_estr; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_linea_estrategica
    ADD CONSTRAINT fk_dependencia_estab_linea_estr FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_establecimiento_empleado; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT fk_dependencia_establecimiento_empleado FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_establecimiento_tipo_servicio; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT fk_dependencia_establecimiento_tipo_servicio FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_informe; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_dependencia_informe FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_orden; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_dependencia_orden FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_padre; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_dependencia_padre FOREIGN KEY (id_dependencia_padre) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_solicitud; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_dependencia_solicitud FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_dependencia_tipo_recurso; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT fk_dependencia_tipo_recurso FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_det_solicitud_det_orden; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT fk_det_solicitud_det_orden FOREIGN KEY (id_detalle_solicitud_servicio) REFERENCES sifda_detalle_solicitud_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_e01bb1a3a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a3a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user_user(id);


--
-- Name: fk_empleado_actividad; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT fk_empleado_actividad FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_empleado_equipo; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT fk_empleado_equipo FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_empleado_informe; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_empleado_informe FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_empleado_subactividad; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT fk_empleado_subactividad FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_establecimiento_dependencia_establecimiento; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_establecimiento_dependencia_establecimiento FOREIGN KEY (id_establecimiento) REFERENCES ctl_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_fos_user_user_bitacora; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT fk_fos_user_user_bitacora FOREIGN KEY (user_id) REFERENCES fos_user_user(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_fos_user_user_dependencia_establecimiento; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user
    ADD CONSTRAINT fk_fos_user_user_dependencia_establecimiento FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_fos_user_user_empleado; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user
    ADD CONSTRAINT fk_fos_user_user_empleado FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_informe_recurso; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT fk_informe_recurso FOREIGN KEY (id_informe_orden_trabajo) REFERENCES sifda_informe_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_linea_estrategica_actividad; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT fk_linea_estrategica_actividad FOREIGN KEY (id_linea_estrategica) REFERENCES sidpla_linea_estrategica(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_orden_det_orden; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT fk_orden_det_orden FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_orden_equipo; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT fk_orden_equipo FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_orden_retraso; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_retraso_actividad
    ADD CONSTRAINT fk_orden_retraso FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_orden_trabajo_informe; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_orden_trabajo_informe FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_orden_tracking; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_orden_tracking FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_ruta_ciclo_etapa; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT fk_ruta_ciclo_etapa FOREIGN KEY (id_etapa) REFERENCES sifda_ruta_ciclo_vida(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_ruta_ciclo_tracking; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_ruta_ciclo_tracking FOREIGN KEY (id_etapa) REFERENCES sifda_ruta_ciclo_vida(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_solicitud_detalle_solic; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_servicio
    ADD CONSTRAINT fk_solicitud_detalle_solic FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_solicitud_orden; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_solicitud_orden FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_solicitud_reprogramacion; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_reprogramacion_servicio
    ADD CONSTRAINT fk_solicitud_reprogramacion FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_subactividad_informe; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_subactividad_informe FOREIGN KEY (id_subactividad) REFERENCES sidpla_subactividad(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipo_dependencia_dependencia; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia
    ADD CONSTRAINT fk_tipo_dependencia_dependencia FOREIGN KEY (id_tipo_dependencia) REFERENCES ctl_tipo_dependencia(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipo_recurso_recurso; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT fk_tipo_recurso_recurso FOREIGN KEY (id_tipo_recurso_dependencia) REFERENCES sifda_tipo_recurso_dependencia(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipo_recurso_tipo_dep; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT fk_tipo_recurso_tipo_dep FOREIGN KEY (id_tipo_recurso) REFERENCES sifda_tipo_recurso(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipo_servicio_ruta_ciclo; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT fk_tipo_servicio_ruta_ciclo FOREIGN KEY (id_tipo_servicio) REFERENCES sifda_tipo_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipo_servicio_solicitud; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_tipo_servicio_solicitud FOREIGN KEY (id_tipo_servicio) REFERENCES sifda_tipo_servicio(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--
