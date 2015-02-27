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
    id_evento integer,
    user_id integer,
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
    id_dependencia integer,
    id_dependencia_padre integer,
    id_establecimiento integer,
    abreviatura character varying(255) DEFAULT NULL::character varying,
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
    id_cargo integer,
    id_dependencia_establecimiento integer,
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
    username_canonical character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_canonical character varying(255) NOT NULL,
    enabled boolean NOT NULL,
    salt character varying(255) NOT NULL,
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
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    date_of_birth timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    firstname character varying(64) DEFAULT NULL::character varying,
    lastname character varying(64) DEFAULT NULL::character varying,
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
-- Name: fos_user_user_group; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE fos_user_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.fos_user_user_group OWNER TO sifda;

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
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.role_id_seq OWNER TO sifda;

--
-- Name: sidpla_actividad; Type: TABLE; Schema: public; Owner: sifda; Tablespace: 
--

CREATE TABLE sidpla_actividad (
    id integer NOT NULL,
    id_empleado integer,
    id_linea_estrategica integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    activo boolean NOT NULL,
    meta_anual numeric(5,2) NOT NULL,
    descripcion_meta_anual character varying(50) NOT NULL,
    indicador text NOT NULL,
    medio_verificacion character varying(300) NOT NULL,
    generado boolean NOT NULL
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
    medio_verificacion character varying(300) DEFAULT NULL::character varying
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
    id_empleado integer,
    id_orden_trabajo integer,
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
    id_dependencia_establecimiento integer,
    id_empleado integer,
    id_orden_trabajo integer,
    id_subactividad integer,
    id_etapa integer,
    detalle text NOT NULL,
    fecha_realizacion timestamp(0) without time zone NOT NULL,
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
    id_prioridad integer,
    id_estado integer,
    id_dependencia_establecimiento integer,
    id_solicitud_servicio integer,
    id_etapa integer,
    descripcion text NOT NULL,
    codigo character varying(15) NOT NULL,
    fecha_creacion timestamp(0) without time zone NOT NULL,
    fecha_finalizacion timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
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
    fecha_anterior date NOT NULL,
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
    id_etapa integer,
    id_tipo_servicio integer,
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
    id_estado integer,
    id_medio_solicita integer,
    id_dependencia_establecimiento integer,
    user_id integer,
    id_tipo_servicio integer,
    descripcion text NOT NULL,
    fecha_recepcion timestamp(0) without time zone NOT NULL,
    fecha_requiere timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
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
    id_dependencia_establecimiento integer,
    id_tipo_recurso integer,
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
    id_dependencia_establecimiento integer,
    nombre character varying(75) NOT NULL,
    descripcion text NOT NULL,
    activo boolean NOT NULL
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
    id_estado integer,
    id_orden_trabajo integer,
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
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: sifda
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO sifda;

--
-- Name: vwetapassolicitud; Type: VIEW; Schema: public; Owner: sifda
--

CREATE VIEW vwetapassolicitud AS
    SELECT row_number() OVER (ORDER BY rcv.jerarquia, srcv.jerarquia) AS id, ss.id AS id_solicitud, ss.descripcion AS dsc_solicitud, ss.fecha_recepcion AS fchrecep_solicitud, ss.fecha_requiere AS fchreq_solicitud, ts.id AS id_tipo_servicio, ts.nombre AS nombre_tipo_servicio, ts.descripcion AS dsc_tipo_servicio, rcv.id AS id_ciclo_vida, rcv.jerarquia AS jerar_ciclo_vida, rcv.descripcion AS dsc_ciclo_vida, rcv.peso AS etapa_peso, rcv.ignorar_sig AS ignorar_sig_etapa, srcv.id AS id_subetapa, srcv.descripcion AS dsc_subetapa, srcv.peso AS subetapa_peso, srcv.jerarquia AS jerarquia_subetapa, srcv.ignorar_sig AS ignorar_sig_subetapa, ot.id AS id_orden, ot.descripcion AS dsc_orden, ot.fecha_creacion AS fchcrea_orden, ot.fecha_finalizacion AS fchfin_orden, COALESCE(cd.id, 0) AS id_estado, COALESCE(cd.descripcion, 'Sin Asignar'::character varying) AS dsc_estado, e.id AS id_empleado, (((e.nombre)::text || ' '::text) || (e.apellido)::text) AS nom_empleado, e.id_dependencia_establecimiento AS depen_estab FROM (((((((sifda_solicitud_servicio ss LEFT JOIN sifda_tipo_servicio ts ON ((ss.id_tipo_servicio = ts.id))) LEFT JOIN sifda_ruta_ciclo_vida rcv ON ((ts.id = rcv.id_tipo_servicio))) LEFT JOIN sifda_orden_trabajo ot ON (((ot.id_etapa = rcv.id) AND (ot.id_solicitud_servicio = ss.id)))) LEFT JOIN catalogo_detalle cd ON ((cd.id = ot.id_estado))) LEFT JOIN sifda_equipo_trabajo et ON (((et.id_orden_trabajo = ot.id) AND (et.responsable_equipo = true)))) LEFT JOIN ctl_empleado e ON ((et.id_empleado = e.id))) LEFT JOIN (SELECT subetapa.id, subetapa.id_etapa, subetapa.descripcion, subetapa.jerarquia, subetapa.peso, subetapa.ignorar_sig FROM sifda_ruta_ciclo_vida subetapa WHERE (subetapa.id_etapa IS NOT NULL) ORDER BY subetapa.jerarquia) srcv ON ((srcv.id_etapa = rcv.id))) WHERE (rcv.id_etapa IS NULL) ORDER BY rcv.jerarquia, srcv.jerarquia;


ALTER TABLE public.vwetapassolicitud OWNER TO sifda;

--
-- Data for Name: bitacora; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY bitacora (id, id_evento, user_id, fecha_evento, observacion) FROM stdin;
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

SELECT pg_catalog.setval('catalogo_id_seq', 1, false);


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

COPY ctl_dependencia_establecimiento (id, id_dependencia, id_dependencia_padre, id_establecimiento, abreviatura, habilitado) FROM stdin;
2	1	\N	2	\N	t
3	3	\N	2	\N	t
5	5	\N	2	\N	t
6	7	\N	2	\N	t
7	8	\N	2	\N	t
8	9	\N	2	\N	t
9	10	\N	2	\N	t
10	11	\N	2	\N	t
11	12	\N	2	\N	t
12	13	\N	2	\N	t
13	14	\N	2	\N	t
14	15	\N	2	\N	t
15	16	\N	2	\N	t
16	17	\N	2	\N	t
17	18	\N	2	\N	t
18	19	\N	2	\N	t
19	20	\N	2	\N	t
20	21	\N	2	\N	t
22	24	\N	2	\N	t
23	25	\N	2	\N	t
24	26	\N	2	\N	t
25	27	\N	2	\N	t
26	28	\N	2	\N	t
27	31	\N	2	\N	t
28	32	\N	2	\N	t
29	33	\N	2	\N	t
30	34	\N	2	\N	t
31	35	\N	2	\N	t
32	36	\N	2	\N	t
33	37	\N	2	\N	t
34	38	\N	2	\N	t
35	39	\N	2	\N	t
36	40	\N	2	\N	t
37	41	\N	2	\N	t
38	42	\N	2	\N	t
39	43	\N	2	\N	t
40	44	\N	2	\N	t
41	45	\N	2	\N	t
42	46	\N	2	\N	t
43	47	\N	2	\N	t
44	48	\N	2	\N	t
45	49	\N	2	\N	t
46	50	\N	2	\N	t
47	51	\N	2	\N	t
48	52	\N	2	\N	t
49	53	\N	2	\N	t
50	54	\N	2	\N	t
51	55	\N	2	\N	t
52	56	\N	2	\N	t
53	57	\N	2	\N	t
54	58	\N	2	\N	t
55	59	\N	2	\N	t
56	60	\N	2	\N	t
57	61	\N	2	\N	t
58	62	\N	2	\N	t
59	70	\N	2	\N	t
60	71	\N	2	\N	t
61	72	\N	2	\N	t
62	73	\N	2	\N	t
63	74	\N	2	\N	t
64	80	\N	2	\N	t
65	81	\N	2	\N	t
66	82	\N	2	\N	t
67	83	\N	2	\N	t
68	84	\N	2	\N	t
69	85	\N	2	\N	t
70	86	\N	2	\N	t
71	87	\N	2	\N	t
72	88	\N	2	\N	t
73	89	\N	2	\N	t
74	90	\N	2	\N	t
75	91	\N	2	\N	t
76	92	\N	2	\N	t
77	93	\N	2	\N	t
78	94	\N	2	\N	t
79	95	\N	2	\N	t
80	96	\N	2	\N	t
81	97	\N	2	\N	t
82	100	\N	2	\N	t
83	101	\N	2	\N	t
84	5	\N	3	\N	t
85	10	\N	3	\N	t
86	65	\N	3	\N	t
87	76	\N	3	\N	t
88	5	\N	4	\N	t
89	10	\N	4	\N	t
90	65	\N	4	\N	t
91	76	\N	4	\N	t
92	5	\N	5	\N	t
93	10	\N	5	\N	t
94	65	\N	5	\N	t
95	76	\N	5	\N	t
96	5	\N	6	\N	t
97	10	\N	6	\N	t
98	65	\N	6	\N	t
99	76	\N	6	\N	t
100	5	\N	7	\N	t
101	10	\N	7	\N	t
102	65	\N	7	\N	t
103	76	\N	7	\N	t
104	63	\N	9	\N	t
105	63	\N	10	\N	t
106	63	\N	11	\N	t
107	63	\N	12	\N	t
108	63	\N	13	\N	t
109	63	\N	14	\N	t
110	63	\N	15	\N	t
111	63	\N	16	\N	t
112	63	\N	17	\N	t
113	63	\N	18	\N	t
114	63	\N	19	\N	t
115	63	\N	20	\N	t
116	63	\N	21	\N	t
117	63	\N	22	\N	t
118	63	\N	23	\N	t
119	63	\N	24	\N	t
120	63	\N	25	\N	t
121	63	\N	26	\N	t
122	63	\N	27	\N	t
123	63	\N	28	\N	t
124	63	\N	29	\N	t
125	63	\N	30	\N	t
126	63	\N	31	\N	t
127	63	\N	32	\N	t
128	63	\N	33	\N	t
129	63	\N	34	\N	t
130	63	\N	35	\N	t
131	3	\N	36	\N	t
132	4	\N	36	\N	t
133	5	\N	36	\N	t
134	6	\N	36	\N	t
135	10	\N	36	\N	t
136	18	\N	36	\N	t
137	23	\N	36	\N	t
138	63	\N	36	\N	t
139	75	\N	36	\N	t
140	76	\N	36	\N	t
141	84	\N	36	\N	t
142	98	\N	36	\N	t
143	99	\N	36	\N	t
144	63	\N	37	\N	t
145	63	\N	38	\N	t
146	3	\N	39	\N	t
147	4	\N	39	\N	t
148	6	\N	39	\N	t
149	10	\N	39	\N	t
150	18	\N	39	\N	t
151	23	\N	39	\N	t
152	63	\N	39	\N	t
153	75	\N	39	\N	t
154	76	\N	39	\N	t
155	84	\N	39	\N	t
156	98	\N	39	\N	t
157	99	\N	39	\N	t
158	63	\N	40	\N	t
159	63	\N	41	\N	t
160	3	\N	42	\N	t
161	4	\N	42	\N	t
162	5	\N	42	\N	t
163	6	\N	42	\N	t
164	10	\N	42	\N	t
165	18	\N	42	\N	t
166	23	\N	42	\N	t
167	63	\N	42	\N	t
168	75	\N	42	\N	t
169	76	\N	42	\N	t
170	84	\N	42	\N	t
171	98	\N	42	\N	t
172	99	\N	42	\N	t
173	63	\N	43	\N	t
174	63	\N	44	\N	t
175	63	\N	45	\N	t
176	63	\N	46	\N	t
177	63	\N	47	\N	t
178	63	\N	48	\N	t
179	63	\N	49	\N	t
180	63	\N	50	\N	t
181	63	\N	51	\N	t
182	63	\N	52	\N	t
183	63	\N	53	\N	t
184	63	\N	54	\N	t
185	63	\N	55	\N	t
186	63	\N	56	\N	t
187	63	\N	57	\N	t
188	63	\N	58	\N	t
189	63	\N	59	\N	t
190	63	\N	60	\N	t
191	63	\N	61	\N	t
192	63	\N	62	\N	t
193	63	\N	63	\N	t
194	63	\N	64	\N	t
195	63	\N	65	\N	t
196	63	\N	66	\N	t
197	63	\N	67	\N	t
198	63	\N	68	\N	t
199	63	\N	69	\N	t
200	63	\N	70	\N	t
201	63	\N	71	\N	t
202	63	\N	72	\N	t
203	63	\N	73	\N	t
204	63	\N	74	\N	t
205	63	\N	75	\N	t
206	63	\N	76	\N	t
207	63	\N	77	\N	t
208	63	\N	78	\N	t
209	63	\N	79	\N	t
210	63	\N	80	\N	t
211	63	\N	81	\N	t
212	63	\N	82	\N	t
213	63	\N	83	\N	t
214	63	\N	84	\N	t
215	63	\N	85	\N	t
216	63	\N	86	\N	t
217	63	\N	87	\N	t
218	63	\N	88	\N	t
219	63	\N	89	\N	t
220	63	\N	90	\N	t
221	63	\N	91	\N	t
222	63	\N	92	\N	t
223	63	\N	93	\N	t
224	63	\N	94	\N	t
225	63	\N	95	\N	t
226	63	\N	96	\N	t
227	63	\N	97	\N	t
228	63	\N	98	\N	t
229	63	\N	100	\N	t
230	63	\N	100	\N	t
231	63	\N	101	\N	t
232	63	\N	102	\N	t
233	63	\N	103	\N	t
234	63	\N	104	\N	t
235	63	\N	105	\N	t
236	63	\N	106	\N	t
237	63	\N	107	\N	t
238	3	\N	108	\N	t
239	4	\N	108	\N	t
240	5	\N	108	\N	t
241	6	\N	108	\N	t
242	10	\N	108	\N	t
243	18	\N	108	\N	t
244	23	\N	108	\N	t
245	63	\N	108	\N	t
246	75	\N	108	\N	t
247	76	\N	108	\N	t
248	84	\N	108	\N	t
249	98	\N	108	\N	t
250	99	\N	108	\N	t
251	63	\N	109	\N	t
252	63	\N	110	\N	t
253	63	\N	111	\N	t
254	3	\N	112	\N	t
255	4	\N	112	\N	t
256	5	\N	112	\N	t
257	6	\N	112	\N	t
258	10	\N	112	\N	t
259	18	\N	112	\N	t
260	23	\N	112	\N	t
261	63	\N	112	\N	t
262	75	\N	112	\N	t
263	76	\N	112	\N	t
264	84	\N	112	\N	t
265	98	\N	112	\N	t
266	99	\N	112	\N	t
267	63	\N	113	\N	t
268	63	\N	114	\N	t
269	63	\N	115	\N	t
270	63	\N	116	\N	t
271	63	\N	117	\N	t
272	63	\N	118	\N	t
273	63	\N	119	\N	t
274	63	\N	120	\N	t
275	63	\N	121	\N	t
276	63	\N	122	\N	t
277	63	\N	123	\N	t
278	63	\N	124	\N	t
279	63	\N	125	\N	t
280	63	\N	126	\N	t
281	63	\N	127	\N	t
282	63	\N	128	\N	t
283	63	\N	129	\N	t
284	63	\N	130	\N	t
285	63	\N	131	\N	t
286	63	\N	132	\N	t
287	63	\N	133	\N	t
288	63	\N	134	\N	t
289	63	\N	135	\N	t
290	63	\N	136	\N	t
291	63	\N	137	\N	t
292	63	\N	138	\N	t
293	63	\N	139	\N	t
294	63	\N	140	\N	t
295	63	\N	141	\N	t
296	63	\N	142	\N	t
297	63	\N	143	\N	t
298	63	\N	144	\N	t
299	63	\N	145	\N	t
300	63	\N	146	\N	t
301	63	\N	147	\N	t
302	63	\N	148	\N	t
303	63	\N	149	\N	t
304	63	\N	150	\N	t
305	63	\N	151	\N	t
306	63	\N	152	\N	t
307	63	\N	153	\N	t
308	63	\N	154	\N	t
309	63	\N	155	\N	t
310	63	\N	156	\N	t
311	63	\N	157	\N	t
312	63	\N	158	\N	t
313	63	\N	159	\N	t
314	63	\N	160	\N	t
315	63	\N	161	\N	t
316	63	\N	162	\N	t
317	63	\N	163	\N	t
318	63	\N	164	\N	t
319	3	\N	165	\N	t
320	4	\N	165	\N	t
321	5	\N	165	\N	t
323	10	\N	165	\N	t
324	18	\N	165	\N	t
325	23	\N	165	\N	t
326	63	\N	165	\N	t
327	75	\N	165	\N	t
328	76	\N	165	\N	t
329	84	\N	165	\N	t
330	98	\N	165	\N	t
331	99	\N	165	\N	t
332	63	\N	166	\N	t
333	63	\N	167	\N	t
334	63	\N	168	\N	t
335	63	\N	169	\N	t
336	63	\N	170	\N	t
337	63	\N	171	\N	t
338	63	\N	172	\N	t
339	63	\N	173	\N	t
340	63	\N	174	\N	t
341	63	\N	175	\N	t
342	63	\N	176	\N	t
343	63	\N	177	\N	t
344	63	\N	178	\N	t
345	63	\N	179	\N	t
346	63	\N	180	\N	t
347	63	\N	181	\N	t
348	63	\N	182	\N	t
349	63	\N	183	\N	t
350	63	\N	184	\N	t
351	63	\N	185	\N	t
352	63	\N	186	\N	t
353	63	\N	187	\N	t
354	63	\N	188	\N	t
355	63	\N	189	\N	t
356	63	\N	190	\N	t
357	63	\N	191	\N	t
358	63	\N	192	\N	t
359	63	\N	193	\N	t
360	63	\N	194	\N	t
361	63	\N	195	\N	t
362	63	\N	196	\N	t
363	63	\N	197	\N	t
364	63	\N	198	\N	t
365	63	\N	199	\N	t
366	63	\N	200	\N	t
367	63	\N	201	\N	t
368	63	\N	202	\N	t
369	63	\N	203	\N	t
370	3	\N	204	\N	t
371	4	\N	204	\N	t
372	5	\N	204	\N	t
373	6	\N	204	\N	t
374	10	\N	204	\N	t
375	18	\N	204	\N	t
376	23	\N	204	\N	t
377	63	\N	204	\N	t
378	75	\N	204	\N	t
379	76	\N	204	\N	t
380	84	\N	204	\N	t
381	98	\N	204	\N	t
382	99	\N	204	\N	t
383	63	\N	205	\N	t
384	63	\N	206	\N	t
385	3	\N	207	\N	t
386	4	\N	207	\N	t
387	5	\N	207	\N	t
388	6	\N	207	\N	t
389	10	\N	207	\N	t
390	18	\N	207	\N	t
391	23	\N	207	\N	t
392	63	\N	207	\N	t
393	75	\N	207	\N	t
394	76	\N	207	\N	t
395	84	\N	207	\N	t
396	98	\N	207	\N	t
397	99	\N	207	\N	t
398	63	\N	208	\N	t
399	63	\N	209	\N	t
400	63	\N	210	\N	t
401	63	\N	211	\N	t
402	63	\N	212	\N	t
403	63	\N	213	\N	t
404	63	\N	214	\N	t
405	63	\N	215	\N	t
406	63	\N	216	\N	t
407	63	\N	217	\N	t
408	63	\N	218	\N	t
409	63	\N	219	\N	t
410	63	\N	220	\N	t
411	63	\N	221	\N	t
412	63	\N	222	\N	t
413	63	\N	223	\N	t
414	63	\N	224	\N	t
415	63	\N	224	\N	t
416	63	\N	226	\N	t
417	63	\N	227	\N	t
418	3	\N	228	\N	t
419	4	\N	228	\N	t
420	5	\N	228	\N	t
421	6	\N	228	\N	t
422	10	\N	228	\N	t
423	18	\N	228	\N	t
424	23	\N	228	\N	t
425	63	\N	228	\N	t
426	75	\N	228	\N	t
427	76	\N	228	\N	t
428	84	\N	228	\N	t
429	98	\N	228	\N	t
430	99	\N	228	\N	t
431	63	\N	229	\N	t
432	63	\N	230	\N	t
433	63	\N	231	\N	t
434	63	\N	232	\N	t
435	63	\N	233	\N	t
436	63	\N	234	\N	t
1102	63	\N	235	\N	t
437	63	\N	236	\N	t
438	63	\N	237	\N	t
439	63	\N	238	\N	t
440	63	\N	239	\N	t
441	63	\N	240	\N	t
442	63	\N	241	\N	t
443	63	\N	242	\N	t
444	63	\N	243	\N	t
445	63	\N	244	\N	t
446	63	\N	245	\N	t
447	63	\N	246	\N	t
448	63	\N	247	\N	t
449	84	\N	247	\N	t
450	63	\N	248	\N	t
451	63	\N	249	\N	t
452	63	\N	250	\N	t
453	63	\N	251	\N	t
454	63	\N	252	\N	t
455	63	\N	253	\N	t
456	63	\N	254	\N	t
457	63	\N	255	\N	t
458	63	\N	256	\N	t
459	63	\N	257	\N	t
460	63	\N	258	\N	t
461	63	\N	259	\N	t
462	63	\N	260	\N	t
463	63	\N	261	\N	t
464	63	\N	262	\N	t
465	63	\N	263	\N	t
466	63	\N	264	\N	t
467	63	\N	265	\N	t
468	63	\N	266	\N	t
469	63	\N	267	\N	t
470	63	\N	268	\N	t
471	63	\N	269	\N	t
472	63	\N	270	\N	t
473	63	\N	271	\N	t
474	63	\N	272	\N	t
475	63	\N	273	\N	t
476	3	\N	274	\N	t
477	4	\N	274	\N	t
478	5	\N	274	\N	t
479	6	\N	274	\N	t
480	10	\N	274	\N	t
481	18	\N	274	\N	t
482	23	\N	274	\N	t
483	63	\N	274	\N	t
484	75	\N	274	\N	t
485	76	\N	274	\N	t
486	98	\N	274	\N	t
487	99	\N	274	\N	t
488	63	\N	275	\N	t
489	63	\N	276	\N	t
490	3	\N	277	\N	t
491	4	\N	277	\N	t
492	5	\N	277	\N	t
493	6	\N	277	\N	t
494	10	\N	277	\N	t
495	18	\N	277	\N	t
496	23	\N	277	\N	t
497	63	\N	277	\N	t
498	75	\N	277	\N	t
499	76	\N	277	\N	t
500	84	\N	277	\N	t
501	98	\N	277	\N	t
502	99	\N	277	\N	t
503	63	\N	278	\N	t
504	63	\N	279	\N	t
505	63	\N	280	\N	t
506	63	\N	281	\N	t
507	63	\N	282	\N	t
508	63	\N	283	\N	t
509	63	\N	284	\N	t
510	63	\N	285	\N	t
511	63	\N	286	\N	t
512	63	\N	287	\N	t
513	63	\N	288	\N	t
514	63	\N	289	\N	t
515	63	\N	290	\N	t
516	63	\N	291	\N	t
517	63	\N	292	\N	t
518	63	\N	293	\N	t
519	63	\N	294	\N	t
520	63	\N	295	\N	t
521	63	\N	296	\N	t
522	63	\N	297	\N	t
523	63	\N	298	\N	t
524	63	\N	299	\N	t
525	63	\N	300	\N	t
526	63	\N	301	\N	t
527	63	\N	302	\N	t
528	63	\N	303	\N	t
529	63	\N	304	\N	t
530	63	\N	305	\N	t
531	63	\N	306	\N	t
532	63	\N	307	\N	t
533	63	\N	308	\N	t
534	63	\N	309	\N	t
535	3	\N	310	\N	t
536	4	\N	310	\N	t
537	5	\N	310	\N	t
538	6	\N	310	\N	t
539	10	\N	310	\N	t
540	18	\N	310	\N	t
541	23	\N	310	\N	t
542	63	\N	310	\N	t
543	75	\N	310	\N	t
544	76	\N	310	\N	t
545	84	\N	310	\N	t
546	98	\N	310	\N	t
547	99	\N	310	\N	t
548	63	\N	311	\N	t
549	63	\N	312	\N	t
550	63	\N	313	\N	t
551	63	\N	314	\N	t
552	63	\N	315	\N	t
553	63	\N	316	\N	t
554	63	\N	317	\N	t
555	63	\N	318	\N	t
556	63	\N	319	\N	t
557	63	\N	320	\N	t
558	63	\N	321	\N	t
559	63	\N	322	\N	t
560	63	\N	323	\N	t
561	63	\N	324	\N	t
562	63	\N	325	\N	t
563	63	\N	326	\N	t
564	63	\N	327	\N	t
565	63	\N	328	\N	t
566	63	\N	329	\N	t
567	63	\N	330	\N	t
568	63	\N	331	\N	t
569	3	\N	332	\N	t
570	4	\N	332	\N	t
571	5	\N	332	\N	t
572	6	\N	332	\N	t
573	10	\N	332	\N	t
574	18	\N	332	\N	t
575	23	\N	332	\N	t
576	63	\N	332	\N	t
577	75	\N	332	\N	t
578	76	\N	332	\N	t
579	84	\N	332	\N	t
580	98	\N	332	\N	t
581	99	\N	332	\N	t
582	63	\N	333	\N	t
583	63	\N	334	\N	t
584	63	\N	335	\N	t
585	3	\N	336	\N	t
586	4	\N	336	\N	t
587	5	\N	336	\N	t
588	6	\N	336	\N	t
589	10	\N	336	\N	t
590	18	\N	336	\N	t
591	23	\N	336	\N	t
592	63	\N	336	\N	t
593	75	\N	336	\N	t
594	76	\N	336	\N	t
595	84	\N	336	\N	t
596	98	\N	336	\N	t
597	99	\N	336	\N	t
598	63	\N	337	\N	t
599	63	\N	338	\N	t
600	63	\N	339	\N	t
601	3	\N	340	\N	t
602	4	\N	340	\N	t
603	5	\N	340	\N	t
604	6	\N	340	\N	t
605	10	\N	340	\N	t
606	18	\N	340	\N	t
607	23	\N	340	\N	t
608	63	\N	340	\N	t
609	75	\N	340	\N	t
610	76	\N	340	\N	t
611	84	\N	340	\N	t
612	98	\N	340	\N	t
613	99	\N	340	\N	t
614	63	\N	341	\N	t
615	63	\N	342	\N	t
616	63	\N	343	\N	t
617	63	\N	344	\N	t
618	63	\N	345	\N	t
619	63	\N	346	\N	t
620	63	\N	347	\N	t
621	63	\N	348	\N	t
622	63	\N	349	\N	t
623	63	\N	350	\N	t
624	63	\N	351	\N	t
625	63	\N	352	\N	t
626	63	\N	353	\N	t
627	63	\N	354	\N	t
628	63	\N	355	\N	t
629	63	\N	356	\N	t
630	63	\N	357	\N	t
631	63	\N	358	\N	t
632	63	\N	359	\N	t
633	63	\N	360	\N	t
634	63	\N	361	\N	t
635	63	\N	362	\N	t
636	63	\N	363	\N	t
637	63	\N	364	\N	t
638	63	\N	365	\N	t
639	63	\N	366	\N	t
640	63	\N	367	\N	t
641	63	\N	368	\N	t
642	63	\N	369	\N	t
643	63	\N	370	\N	t
644	63	\N	371	\N	t
645	63	\N	372	\N	t
646	63	\N	373	\N	t
647	63	\N	374	\N	t
648	63	\N	375	\N	t
649	63	\N	376	\N	t
650	63	\N	377	\N	t
651	63	\N	378	\N	t
652	63	\N	379	\N	t
653	3	\N	380	\N	t
654	4	\N	380	\N	t
655	5	\N	380	\N	t
656	6	\N	380	\N	t
657	10	\N	380	\N	t
658	18	\N	380	\N	t
659	23	\N	380	\N	t
660	63	\N	380	\N	t
661	75	\N	380	\N	t
662	76	\N	380	\N	t
663	84	\N	380	\N	t
664	98	\N	380	\N	t
665	99	\N	380	\N	t
666	63	\N	381	\N	t
667	63	\N	382	\N	t
668	3	\N	383	\N	t
669	4	\N	383	\N	t
670	5	\N	383	\N	t
671	6	\N	383	\N	t
672	10	\N	383	\N	t
673	18	\N	383	\N	t
674	23	\N	383	\N	t
675	63	\N	383	\N	t
676	75	\N	383	\N	t
677	76	\N	383	\N	t
678	84	\N	383	\N	t
679	98	\N	383	\N	t
680	99	\N	383	\N	t
681	63	\N	384	\N	t
682	63	\N	385	\N	t
683	3	\N	386	\N	t
684	4	\N	386	\N	t
685	5	\N	386	\N	t
686	6	\N	386	\N	t
687	10	\N	386	\N	t
688	18	\N	386	\N	t
689	23	\N	386	\N	t
690	63	\N	386	\N	t
691	75	\N	386	\N	t
692	76	\N	386	\N	t
693	84	\N	386	\N	t
694	98	\N	386	\N	t
695	99	\N	386	\N	t
696	63	\N	387	\N	t
697	63	\N	388	\N	t
698	63	\N	389	\N	t
699	63	\N	390	\N	t
700	63	\N	391	\N	t
701	63	\N	392	\N	t
702	63	\N	393	\N	t
703	63	\N	394	\N	t
704	63	\N	395	\N	t
705	63	\N	396	\N	t
706	63	\N	397	\N	t
707	63	\N	398	\N	t
708	63	\N	399	\N	t
709	63	\N	400	\N	t
710	63	\N	401	\N	t
711	63	\N	402	\N	t
712	63	\N	403	\N	t
713	63	\N	404	\N	t
714	63	\N	405	\N	t
715	63	\N	406	\N	t
716	63	\N	407	\N	t
717	63	\N	408	\N	t
718	63	\N	409	\N	t
719	63	\N	410	\N	t
720	63	\N	411	\N	t
721	63	\N	412	\N	t
722	63	\N	413	\N	t
723	63	\N	414	\N	t
724	63	\N	415	\N	t
725	63	\N	416	\N	t
726	63	\N	417	\N	t
727	63	\N	418	\N	t
728	63	\N	419	\N	t
729	63	\N	420	\N	t
730	63	\N	421	\N	t
731	63	\N	422	\N	t
732	63	\N	423	\N	t
733	63	\N	424	\N	t
734	63	\N	425	\N	t
735	63	\N	426	\N	t
736	63	\N	427	\N	t
737	63	\N	428	\N	t
738	63	\N	429	\N	t
739	63	\N	430	\N	t
740	63	\N	431	\N	t
741	63	\N	432	\N	t
742	63	\N	433	\N	t
743	3	\N	434	\N	t
744	4	\N	434	\N	t
745	5	\N	434	\N	t
746	6	\N	434	\N	t
747	10	\N	434	\N	t
748	18	\N	434	\N	t
749	23	\N	434	\N	t
750	63	\N	434	\N	t
751	75	\N	434	\N	t
752	76	\N	434	\N	t
753	84	\N	434	\N	t
754	98	\N	434	\N	t
755	99	\N	434	\N	t
756	63	\N	435	\N	t
757	63	\N	436	\N	t
758	63	\N	437	\N	t
759	63	\N	438	\N	t
760	63	\N	439	\N	t
761	63	\N	440	\N	t
762	63	\N	441	\N	t
763	63	\N	442	\N	t
764	63	\N	443	\N	t
765	63	\N	444	\N	t
766	63	\N	445	\N	t
767	63	\N	446	\N	t
768	63	\N	447	\N	t
769	63	\N	448	\N	t
770	63	\N	449	\N	t
771	63	\N	450	\N	t
772	63	\N	451	\N	t
773	63	\N	452	\N	t
774	63	\N	453	\N	t
775	63	\N	454	\N	t
776	63	\N	455	\N	t
777	63	\N	456	\N	t
778	63	\N	457	\N	t
779	63	\N	458	\N	t
780	63	\N	459	\N	t
781	63	\N	460	\N	t
782	63	\N	461	\N	t
783	63	\N	462	\N	t
784	63	\N	463	\N	t
785	63	\N	464	\N	t
786	63	\N	465	\N	t
787	63	\N	466	\N	t
788	3	\N	467	\N	t
789	4	\N	467	\N	t
790	5	\N	467	\N	t
791	6	\N	467	\N	t
792	10	\N	467	\N	t
793	18	\N	467	\N	t
794	23	\N	467	\N	t
795	63	\N	467	\N	t
796	75	\N	467	\N	t
797	76	\N	467	\N	t
798	84	\N	467	\N	t
799	98	\N	467	\N	t
800	99	\N	467	\N	t
801	63	\N	468	\N	t
802	63	\N	469	\N	t
803	63	\N	470	\N	t
804	3	\N	471	\N	t
805	4	\N	471	\N	t
806	5	\N	471	\N	t
807	6	\N	471	\N	t
808	10	\N	471	\N	t
809	18	\N	471	\N	t
810	23	\N	471	\N	t
811	63	\N	471	\N	t
812	75	\N	471	\N	t
813	76	\N	471	\N	t
814	84	\N	471	\N	t
815	98	\N	471	\N	t
816	99	\N	471	\N	t
817	63	\N	472	\N	t
818	63	\N	473	\N	t
819	63	\N	474	\N	t
820	63	\N	475	\N	t
821	63	\N	476	\N	t
822	63	\N	477	\N	t
823	63	\N	478	\N	t
824	63	\N	479	\N	t
825	63	\N	480	\N	t
826	63	\N	481	\N	t
827	63	\N	482	\N	t
828	63	\N	483	\N	t
829	63	\N	484	\N	t
830	63	\N	485	\N	t
831	63	\N	486	\N	t
832	63	\N	487	\N	t
833	63	\N	488	\N	t
834	63	\N	489	\N	t
835	63	\N	490	\N	t
836	63	\N	491	\N	t
837	63	\N	492	\N	t
838	63	\N	493	\N	t
839	63	\N	494	\N	t
840	63	\N	495	\N	t
841	63	\N	496	\N	t
842	63	\N	497	\N	t
843	63	\N	498	\N	t
844	63	\N	499	\N	t
845	63	\N	500	\N	t
846	63	\N	501	\N	t
847	63	\N	502	\N	t
848	63	\N	503	\N	t
849	63	\N	504	\N	t
850	63	\N	505	\N	t
851	63	\N	506	\N	t
852	63	\N	507	\N	t
853	63	\N	508	\N	t
854	63	\N	509	\N	t
855	63	\N	510	\N	t
856	63	\N	511	\N	t
857	3	\N	512	\N	t
858	4	\N	512	\N	t
859	5	\N	512	\N	t
860	6	\N	512	\N	t
861	10	\N	512	\N	t
862	18	\N	512	\N	t
863	23	\N	512	\N	t
864	63	\N	512	\N	t
865	75	\N	512	\N	t
866	76	\N	512	\N	t
867	84	\N	512	\N	t
868	98	\N	512	\N	t
869	99	\N	512	\N	t
870	63	\N	513	\N	t
871	63	\N	514	\N	t
872	63	\N	515	\N	t
873	63	\N	516	\N	t
874	63	\N	517	\N	t
875	3	\N	518	\N	t
876	4	\N	518	\N	t
877	5	\N	518	\N	t
878	6	\N	518	\N	t
879	10	\N	518	\N	t
880	18	\N	518	\N	t
881	23	\N	518	\N	t
882	63	\N	518	\N	t
883	75	\N	518	\N	t
884	76	\N	518	\N	t
885	84	\N	518	\N	t
886	98	\N	518	\N	t
887	99	\N	518	\N	t
888	63	\N	519	\N	t
889	63	\N	520	\N	t
890	63	\N	521	\N	t
891	63	\N	522	\N	t
892	3	\N	523	\N	t
893	4	\N	523	\N	t
894	6	\N	523	\N	t
895	10	\N	523	\N	t
896	18	\N	523	\N	t
897	23	\N	523	\N	t
898	63	\N	523	\N	t
899	75	\N	523	\N	t
900	76	\N	523	\N	t
901	84	\N	523	\N	t
902	98	\N	523	\N	t
903	99	\N	523	\N	t
904	63	\N	524	\N	t
905	63	\N	525	\N	t
906	63	\N	526	\N	t
907	63	\N	527	\N	t
908	3	\N	528	\N	t
909	4	\N	528	\N	t
910	5	\N	528	\N	t
911	6	\N	528	\N	t
912	10	\N	528	\N	t
913	18	\N	528	\N	t
914	23	\N	528	\N	t
915	63	\N	528	\N	t
916	75	\N	528	\N	t
917	76	\N	528	\N	t
918	84	\N	528	\N	t
919	98	\N	528	\N	t
920	99	\N	528	\N	t
921	63	\N	529	\N	t
922	63	\N	530	\N	t
923	63	\N	531	\N	t
924	63	\N	532	\N	t
925	3	\N	533	\N	t
926	4	\N	533	\N	t
927	5	\N	533	\N	t
928	6	\N	533	\N	t
929	10	\N	533	\N	t
930	18	\N	533	\N	t
931	23	\N	533	\N	t
932	63	\N	533	\N	t
933	75	\N	533	\N	t
934	76	\N	533	\N	t
935	84	\N	533	\N	t
936	98	\N	533	\N	t
937	99	\N	533	\N	t
938	63	\N	534	\N	t
939	63	\N	535	\N	t
940	3	\N	536	\N	t
941	4	\N	536	\N	t
942	5	\N	536	\N	t
943	6	\N	536	\N	t
944	10	\N	536	\N	t
945	18	\N	536	\N	t
946	23	\N	536	\N	t
947	63	\N	536	\N	t
948	75	\N	536	\N	t
949	76	\N	536	\N	t
950	84	\N	536	\N	t
951	98	\N	536	\N	t
952	99	\N	536	\N	t
953	63	\N	537	\N	t
954	63	\N	538	\N	t
955	3	\N	539	\N	t
956	4	\N	539	\N	t
957	5	\N	539	\N	t
958	6	\N	539	\N	t
959	10	\N	539	\N	t
960	18	\N	539	\N	t
961	23	\N	539	\N	t
962	55	\N	539	\N	t
963	63	\N	539	\N	t
964	75	\N	539	\N	t
965	76	\N	539	\N	t
966	84	\N	539	\N	t
967	98	\N	539	\N	t
968	99	\N	539	\N	t
969	63	\N	540	\N	t
970	63	\N	541	\N	t
971	63	\N	542	\N	t
972	63	\N	543	\N	t
973	63	\N	544	\N	t
974	63	\N	545	\N	t
975	63	\N	546	\N	t
976	63	\N	547	\N	t
977	63	\N	548	\N	t
978	63	\N	549	\N	t
979	63	\N	550	\N	t
980	63	\N	551	\N	t
981	63	\N	552	\N	t
982	63	\N	553	\N	t
983	63	\N	554	\N	t
984	63	\N	555	\N	t
985	63	\N	556	\N	t
986	63	\N	557	\N	t
987	63	\N	558	\N	t
988	63	\N	559	\N	t
989	63	\N	560	\N	t
990	63	\N	561	\N	t
991	63	\N	562	\N	t
992	63	\N	563	\N	t
993	63	\N	564	\N	t
994	63	\N	565	\N	t
995	63	\N	566	\N	t
996	63	\N	567	\N	t
997	63	\N	568	\N	t
998	63	\N	569	\N	t
999	63	\N	570	\N	t
1000	63	\N	571	\N	t
1001	63	\N	572	\N	t
1002	63	\N	573	\N	t
1003	63	\N	574	\N	t
1004	63	\N	575	\N	t
1005	63	\N	576	\N	t
1006	63	\N	577	\N	t
1007	63	\N	578	\N	t
1008	63	\N	579	\N	t
1009	63	\N	580	\N	t
1010	63	\N	581	\N	t
1011	63	\N	582	\N	t
1012	63	\N	583	\N	t
1013	63	\N	584	\N	t
1014	63	\N	585	\N	t
1015	63	\N	586	\N	t
1016	63	\N	587	\N	t
1017	63	\N	588	\N	t
1018	63	\N	589	\N	t
1019	63	\N	590	\N	t
1020	63	\N	591	\N	t
1021	63	\N	592	\N	t
1022	63	\N	593	\N	t
1023	63	\N	594	\N	t
1024	63	\N	595	\N	t
1025	63	\N	596	\N	t
1026	63	\N	597	\N	t
1027	63	\N	598	\N	t
1028	63	\N	599	\N	t
1029	63	\N	600	\N	t
1030	63	\N	601	\N	t
1031	63	\N	602	\N	t
1032	63	\N	603	\N	t
1033	63	\N	604	\N	t
1034	63	\N	605	\N	t
1035	63	\N	606	\N	t
1036	63	\N	607	\N	t
1037	63	\N	608	\N	t
1038	63	\N	609	\N	t
1039	63	\N	610	\N	t
1040	63	\N	611	\N	t
1041	63	\N	612	\N	t
1042	63	\N	613	\N	t
1043	63	\N	614	\N	t
1044	63	\N	615	\N	t
1045	63	\N	616	\N	t
1046	63	\N	617	\N	t
1047	63	\N	618	\N	t
1048	63	\N	619	\N	t
1049	63	\N	620	\N	t
1050	63	\N	621	\N	t
1051	63	\N	622	\N	t
1052	98	\N	622	\N	t
1053	99	\N	622	\N	t
1054	63	\N	623	\N	t
1055	63	\N	624	\N	t
1056	63	\N	625	\N	t
1057	63	\N	626	\N	t
1058	63	\N	627	\N	t
1059	63	\N	628	\N	t
1060	63	\N	629	\N	t
1061	63	\N	630	\N	t
1062	63	\N	631	\N	t
1063	63	\N	632	\N	t
1064	63	\N	633	\N	t
1065	63	\N	634	\N	t
1066	63	\N	635	\N	t
1067	63	\N	636	\N	t
1068	63	\N	637	\N	t
1069	63	\N	638	\N	t
1070	63	\N	639	\N	t
1071	66	\N	640	\N	t
1072	67	\N	640	\N	t
1073	68	\N	640	\N	t
1074	69	\N	640	\N	t
1075	3	\N	641	\N	t
1076	4	\N	641	\N	t
1077	5	\N	641	\N	t
1078	6	\N	641	\N	t
1079	10	\N	641	\N	t
1080	18	\N	641	\N	t
1081	23	\N	641	\N	t
1082	63	\N	641	\N	t
1083	75	\N	641	\N	t
1084	76	\N	641	\N	t
1085	84	\N	641	\N	t
1086	98	\N	641	\N	t
1087	99	\N	641	\N	t
1088	77	\N	642	\N	t
1089	78	\N	643	\N	t
1090	102	\N	643	\N	t
1091	10	\N	644	\N	t
1092	18	\N	644	\N	t
1093	2	\N	645	\N	t
1094	3	\N	645	\N	t
1095	4	\N	645	\N	t
1096	5	\N	645	\N	t
1097	6	\N	645	\N	t
1098	10	\N	645	\N	t
1099	76	\N	645	\N	t
1100	98	\N	645	\N	t
1101	99	\N	645	\N	t
1	4	\N	7	DTIC	t
322	6	\N	165	FARMACIA	t
4	4	\N	2	DTIC	t
21	23	\N	2	UCM	t
\.


--
-- Name: ctl_dependencia_establecimiento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_dependencia_establecimiento_id_seq', 1, false);


--
-- Name: ctl_dependencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_dependencia_id_seq', 1, false);


--
-- Data for Name: ctl_empleado; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY ctl_empleado (id, id_cargo, id_dependencia_establecimiento, nombre, apellido, fecha_nacimiento, correo_electronico) FROM stdin;
1	1	4	Juan	Roque	1985-01-12	roquej@gmail.com
2	2	4	Saul	Aguilar	1991-08-09	saguilar@yahoo.es
3	2	4	Carolina	Perez	1972-06-11	cperez@salud.gob
4	2	4	Leticia	Salamanca	1988-04-12	letty.sal@gmail.com
5	2	4	Oscar	Jimenez	1979-10-21	jimenez.osc979@gmail.com
6	3	4	Marcos	Rivera	1986-10-24	mrivera@salud.gob
7	3	4	Mauricio	Castro	1990-03-27	mcastro@salud.gob
8	3	4	Karla	Guerrero	1984-03-03	kguerrero@salud.gob
9	3	4	Jose	ponce	1987-05-04	jponce@gmail.com
10	1	4	Martin	Reyes	1982-01-03	mreyes@gmail.com
11	1	4	Carlos	Gutierrez	1982-06-01	cgutierres@gmail.com
12	1	4	Pablo	Peña	1982-03-06	cpeña@gmail.com
13	1	4	Emilio	Perla	1972-03-04	epeña@gmail.com
17	1	23	Martin 	Contreras	1985-07-06	axelljt@gmail.com 
14	1	23	Julio 	Martinez	1985-05-10	makakoioi@gmail.com 
16	1	23	Paola	Rivas	1985-06-06	karensita8421@gmail.com
15	1	23	Marta	Reyes	1985-03-12	anthony.huezo@gmail.com 
18	1	23	Mario	Rivera	1987-05-03	karensita8421@gmail.com
19	1	23	Carlos	Duque	1988-04-12	makakoioi@gmail.com
\.


--
-- Name: ctl_empleado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('ctl_empleado_id_seq', 1, false);


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

SELECT pg_catalog.setval('fos_user_group_id_seq', 1, false);


--
-- Data for Name: fos_user_user; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY fos_user_user (id, id_dependencia_establecimiento, id_empleado, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at, created_at, updated_at, date_of_birth, firstname, lastname, website, biography, gender, locale, timezone, phone, facebook_uid, facebook_name, facebook_data, twitter_uid, twitter_name, twitter_data, gplus_uid, gplus_name, gplus_data, token, two_step_code) FROM stdin;
6	4	5	tecnico	tecnico	tecnico@gmail.com	tecnico@gmail.com	t	ox8omybl0j4s4cwcoo0c4ko0cc0gw4s	Xo4x4vwNwt2O+ct6NtpF6jnNA7ugCkZHm+e544TQ3/H4Ya7c/p9pRURTIECH2Izy27p1cCA8hDhyZAgsREgbjA==	2015-02-17 11:35:59	f	f	\N	\N	\N	a:1:{i:0;s:12:"ROLE_TECNICO";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
9	23	15	tecnico1ucm	tecnico1ucm	antony@gmail.com	antony@gmail.com	t	2ak3h1mp7q1wsww4sswggws0ssogs8s	Fbl7P1+eePi4TYGQU2VIX40cb0CKWMHBL2BiWy2Uhc5XGwF/yJ8HwgssaXtvELGqNmARlVGYl5jEJZhQJWUiKA==	2015-02-22 05:47:14	f	f	\N	\N	\N	a:1:{i:0;s:12:"ROLE_TECNICO";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
2	4	2	anthony	anthony	anthony.huezo@gmail.com	anthony.huezo@gmail.com	t	44n26usz7740oswcgggg0kk400w8sgc	6sTUfgUKmPOKq0A+UXVHOOAlilTBvx+r6SCWHFgscRbRmn9TdnTCetnNbklRmTNIOBp8r5PVqFC9QVY66tDHYw==	2015-02-07 02:20:54	f	f	\N	\N	\N	a:0:{}	f	\N	2015-01-28 23:40:51	2015-01-29 12:51:53	\N	Anthony	Huezo	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
7	322	6	solicitante	solicitante	solicitante@gmail.com	solicitante@gmail.com	t	893f5smysj8co4gco4c4cgsss4wk4k4	RvWUS53TELbEEl8WARzzFFwW4O3iHTe63T5lG1bpCY5GhE9WQFQ7JIm490PZdfB9aJioDAPksDgtsr5zIs4uKw==	2015-02-19 08:39:46	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_SOLICITANTE";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
3	4	3	sviana	sviana	sviana@salud.gob.sv	sviana@salud.gob.sv	t	3ik1vjp3h9mo0g4cooos8o00swk808g	a2uCQBSR/lqrmIdgi8+HkvtrklbsY0svXGPp7WkyEVom4MWvr/nNcA/fh4NJKc7LZIKwduDUWsABL+xsiv9RCA==	2015-02-17 11:21:07	f	f	\N	\N	\N	a:0:{}	f	\N	2015-01-29 09:37:24	2015-01-29 11:27:07	\N	Sonia	Viana	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
1	322	1	Minsal	minsal	minsal@salud.gob.sv	minsal@salud.gob.sv	t	nqc2pq64y9wkwk4o0o8o8ossc00skoc	RYzSYKZJbFvpqIUS7AEyN1vQmix9IKNZoIMUmA3jvZQeEVDlECevcOYlCELWoRingMT36/vAtfBmr9rLEsQXaQ==	2015-02-17 11:21:56	f	f	\N	\N	\N	a:2:{i:0;s:16:"ROLE_SUPER_ADMIN";i:1;s:10:"ROLE_ADMIN";}	f	\N	2015-01-27 21:03:12	2015-01-29 11:45:48	\N	Pedro	Perez	\N	\N	u	\N	\N	\N	\N	\N	null	\N	\N	null	\N	\N	null	\N	\N
5	4	4	responsable	responsable	responsable@gmail.com	responsable@gmail.com	t	27nh1rszygpw04o4cogwcskwow0ksc0	i+qNN4ExKVYHVgG3vOknJcMt4vTrH+0qRzn8c/hqPUaUKXyiOgs7CQYn3ep6M3i0UPrTpM3xxkq8PmCkJGVEAw==	2015-02-19 12:04:48	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_RESPONSABLE";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
11	23	16	tecnico2ucm	tecnico2ucm	huezo@gmail.com	huezo@gmail.com	t	owmk6ygpf40woo84c8gcwo8sco08w0o	9vyikSk9xh1A1bG9HJohZkjFEBDZNZ/hk0syYa9NTXFGdqCYj4VeyLVnIZYr9NosZwQKNWtRH8v5pB59BWecUQ==	2015-02-22 05:48:51	f	f	\N	\N	\N	a:1:{i:0;s:12:"ROLE_TECNICO";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
12	\N	\N	tecnico3ucm	tecnico3ucm	mrivera@gmail.com	mrivera@gmail.com	t	2l6uovdpagisgsg8gccoccksks8wgk0	8ifaCoP6U4evsGix9m5n77McjFv8HHyK21f7MCp8YgqGFiDC9R2TwHIrOb5OvFPIqpfC7NdmW4FPCtQ1v65taw==	\N	f	f	\N	\N	\N	a:0:{}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
8	23	14	solicitanteucm	solicitanteucm	emilio@gmail.com	emilio@gmail.com	t	lqhgxxirzxsog4so8ksgg4wk4gossks	seNPeWY3JXksoDnTtq0KbG7LN5stNW15XWoDW0nEkBtp/qekDJxckg1LzmXn3MSVDRJTCIA7uqnYHr6+ab6bHg==	2015-02-23 02:09:09	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_SOLICITANTE";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
10	23	17	responsableucm	responsableucm	axel@gmail.com	axel@gmail.com	t	l3lufubf8ogokk4ogkkc0o0sk0cokgs	87arS95nuqvaPFive4bMThGOY0OH36KptM71irB0xCKH5L3B7lakqlXWF5C72e5vVcRWnDsfBqwhjSBMSVEKTQ==	2015-02-23 02:24:26	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_RESPONSABLE";}	f	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
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

SELECT pg_catalog.setval('fos_user_user_id_seq', 1, false);


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('role_id_seq', 1, false);


--
-- Data for Name: sidpla_actividad; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sidpla_actividad (id, id_empleado, id_linea_estrategica, descripcion, codigo, activo, meta_anual, descripcion_meta_anual, indicador, medio_verificacion, generado) FROM stdin;
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

COPY sifda_equipo_trabajo (id, id_empleado, id_orden_trabajo, responsable_equipo) FROM stdin;
\.


--
-- Name: sifda_equipo_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_equipo_trabajo_id_seq', 1, false);


--
-- Data for Name: sifda_informe_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_informe_orden_trabajo (id, id_dependencia_establecimiento, id_empleado, id_orden_trabajo, id_subactividad, id_etapa, detalle, fecha_realizacion, fecha_registro, terminado) FROM stdin;
\.


--
-- Name: sifda_informe_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_informe_orden_trabajo_id_seq', 1, false);


--
-- Data for Name: sifda_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_orden_trabajo (id, id_prioridad, id_estado, id_dependencia_establecimiento, id_solicitud_servicio, id_etapa, descripcion, codigo, fecha_creacion, fecha_finalizacion, observacion) FROM stdin;
\.


--
-- Name: sifda_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_orden_trabajo_id_seq', 1, false);


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

COPY sifda_reprogramacion_servicio (id, id_solicitud_servicio, fecha_reprogramacion, fecha_anterior, justificacion) FROM stdin;
\.


--
-- Name: sifda_reprogramacion_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_reprogramacion_servicio_id_seq', 1, false);


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

COPY sifda_ruta_ciclo_vida (id, id_etapa, id_tipo_servicio, descripcion, referencia, jerarquia, ignorar_sig, peso) FROM stdin;
\.


--
-- Name: sifda_ruta_ciclo_vida_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_ruta_ciclo_vida_id_seq', 1, false);


--
-- Name: sifda_ruta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_ruta_id_seq', 1, false);


--
-- Data for Name: sifda_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_solicitud_servicio (id, id_estado, id_medio_solicita, id_dependencia_establecimiento, user_id, id_tipo_servicio, descripcion, fecha_recepcion, fecha_requiere) FROM stdin;
\.


--
-- Name: sifda_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: sifda_tipo_recurso; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tipo_recurso (id, nombre, descripcion, rrhh) FROM stdin;
\.


--
-- Data for Name: sifda_tipo_recurso_dependencia; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tipo_recurso_dependencia (id, id_dependencia_establecimiento, id_tipo_recurso, costo_unitario) FROM stdin;
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

COPY sifda_tipo_servicio (id, id_actividad, id_dependencia_establecimiento, nombre, descripcion, activo) FROM stdin;
1	\N	21	Carpintería	Carpintería	t
2	\N	21	Albañilería	Albañilería	t
3	\N	21	Fontanería	Fontanería	t
4	\N	21	Pintura General	Pintura General	t
5	\N	21	Pintura Mobiliario	Pintura Mobiliario	t
6	\N	21	Reparación Mecánica	Reparación Mecánica	t
7	\N	21	Reparación Eléctrica	Reparación Eléctrica	t
8	\N	21	Equipo de oficina	Equipo de oficina	t
9	\N	21	Reparación Telefónica	Reparación Telefónica	t
10	\N	21	Aire Acondicionado	Aire Acondicionado	t
11	\N	21	Reparación Electrónica	Reparación Electrónica	t
12	\N	21	Mantenimiento General de maquinaria	Mantenimiento General de maquinaría	t
13	\N	21	Estimación de reparaciones y costos	Estimación de reparaciones y costos	t
14	\N	21	Ensamblar Maquinas	Ensamblar Maquinas	t
15	\N	21	Ensamblar Equipos	Emsamblar Equipos	t
16	\N	21	Reparación de Cisterna	Reparación de Cistera	t
17	\N	21	Evaluación de instalaciones eléctricas	Evaluación de instalaciones eléctricas	t
18	\N	21	Mantenimiento preventivo de Maquinaría	Mantenimiento preventivo de Maquinaría	t
19	\N	21	Mantenimiento correctivo de maquinaría	Mantenimiento Correctivo de Maquinaría	t
20	\N	21	Limpieza en Fotocopiadoras	Limpieza en Fotocopiadoras	t
21	\N	21	Mantenimiento Automotriz	Mantenimiento Automotriz	t
\.


--
-- Name: sifda_tipo_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tipo_servicio_id_seq', 1, false);


--
-- Data for Name: sifda_tracking_estado; Type: TABLE DATA; Schema: public; Owner: sifda
--

COPY sifda_tracking_estado (id, id_estado, id_orden_trabajo, id_etapa, fecha_inicio, fecha_fin, prog_actividad, observacion) FROM stdin;
\.


--
-- Name: sifda_tracking_estado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('sifda_tracking_estado_id_seq', 1, false);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sifda
--

SELECT pg_catalog.setval('user_id_seq', 1, false);


--
-- Name: bitacora_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT bitacora_pkey PRIMARY KEY (id);


--
-- Name: catalogo_detalle_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY catalogo_detalle
    ADD CONSTRAINT catalogo_detalle_pkey PRIMARY KEY (id);


--
-- Name: catalogo_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY catalogo
    ADD CONSTRAINT catalogo_pkey PRIMARY KEY (id);


--
-- Name: ctl_cargo_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_cargo
    ADD CONSTRAINT ctl_cargo_pkey PRIMARY KEY (id);


--
-- Name: ctl_dependencia_establecimiento_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT ctl_dependencia_establecimiento_pkey PRIMARY KEY (id);


--
-- Name: ctl_dependencia_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_dependencia
    ADD CONSTRAINT ctl_dependencia_pkey PRIMARY KEY (id);


--
-- Name: ctl_empleado_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT ctl_empleado_pkey PRIMARY KEY (id);


--
-- Name: ctl_establecimiento_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_establecimiento
    ADD CONSTRAINT ctl_establecimiento_pkey PRIMARY KEY (id);


--
-- Name: ctl_tipo_dependencia_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY ctl_tipo_dependencia
    ADD CONSTRAINT ctl_tipo_dependencia_pkey PRIMARY KEY (id);


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
-- Name: sidpla_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT sidpla_actividad_pkey PRIMARY KEY (id);


--
-- Name: sidpla_linea_estrategica_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_linea_estrategica
    ADD CONSTRAINT sidpla_linea_estrategica_pkey PRIMARY KEY (id);


--
-- Name: sidpla_subactividad_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT sidpla_subactividad_pkey PRIMARY KEY (id);


--
-- Name: sifda_detalle_solicitud_orden_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT sifda_detalle_solicitud_orden_pkey PRIMARY KEY (id);


--
-- Name: sifda_detalle_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_detalle_solicitud_servicio
    ADD CONSTRAINT sifda_detalle_solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: sifda_equipo_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT sifda_equipo_trabajo_pkey PRIMARY KEY (id);


--
-- Name: sifda_informe_orden_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT sifda_informe_orden_trabajo_pkey PRIMARY KEY (id);


--
-- Name: sifda_orden_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT sifda_orden_trabajo_pkey PRIMARY KEY (id);


--
-- Name: sifda_recurso_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT sifda_recurso_servicio_pkey PRIMARY KEY (id);


--
-- Name: sifda_reprogramacion_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_reprogramacion_servicio
    ADD CONSTRAINT sifda_reprogramacion_servicio_pkey PRIMARY KEY (id);


--
-- Name: sifda_retraso_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_retraso_actividad
    ADD CONSTRAINT sifda_retraso_actividad_pkey PRIMARY KEY (id);


--
-- Name: sifda_ruta_ciclo_vida_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT sifda_ruta_ciclo_vida_pkey PRIMARY KEY (id);


--
-- Name: sifda_ruta_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_ruta
    ADD CONSTRAINT sifda_ruta_pkey PRIMARY KEY (id);


--
-- Name: sifda_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT sifda_solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: sifda_tipo_recurso_dependencia_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT sifda_tipo_recurso_dependencia_pkey PRIMARY KEY (id);


--
-- Name: sifda_tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_recurso
    ADD CONSTRAINT sifda_tipo_recurso_pkey PRIMARY KEY (id);


--
-- Name: sifda_tipo_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT sifda_tipo_servicio_pkey PRIMARY KEY (id);


--
-- Name: sifda_tracking_estado_pkey; Type: CONSTRAINT; Schema: public; Owner: sifda; Tablespace: 
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT sifda_tracking_estado_pkey PRIMARY KEY (id);


--
-- Name: define_etapa_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX define_etapa_fk ON sifda_orden_trabajo USING btree (id_etapa);


--
-- Name: idx_144207df49e7be1; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_144207df49e7be1 ON ctl_dependencia USING btree (id_tipo_dependencia);


--
-- Name: idx_1f50e2dc890253c7; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_1f50e2dc890253c7 ON sifda_equipo_trabajo USING btree (id_empleado);


--
-- Name: idx_1f50e2dcff707141; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_1f50e2dcff707141 ON sifda_equipo_trabajo USING btree (id_orden_trabajo);


--
-- Name: idx_238434196d903705; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_238434196d903705 ON sifda_detalle_solicitud_orden USING btree (id_detalle_solicitud_servicio);


--
-- Name: idx_23843419ff707141; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_23843419ff707141 ON sifda_detalle_solicitud_orden USING btree (id_orden_trabajo);


--
-- Name: idx_24d08fe93a5c634d; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_24d08fe93a5c634d ON sifda_informe_orden_trabajo USING btree (id_subactividad);


--
-- Name: idx_24d08fe9890253c7; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_24d08fe9890253c7 ON sifda_informe_orden_trabajo USING btree (id_empleado);


--
-- Name: idx_24d08fe9e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_24d08fe9e54d836e ON sifda_informe_orden_trabajo USING btree (id_dependencia_establecimiento);


--
-- Name: idx_24d08fe9ff707141; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_24d08fe9ff707141 ON sifda_informe_orden_trabajo USING btree (id_orden_trabajo);


--
-- Name: idx_26b328fea36b7986; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_26b328fea36b7986 ON sifda_ruta_ciclo_vida USING btree (id_tipo_servicio);


--
-- Name: idx_26b328fec1b7f0f4; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_26b328fec1b7f0f4 ON sifda_ruta_ciclo_vida USING btree (id_etapa);


--
-- Name: idx_34aefc666a540e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_34aefc666a540e ON sifda_tracking_estado USING btree (id_estado);


--
-- Name: idx_34aefc66c1b7f0f4; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_34aefc66c1b7f0f4 ON sifda_tracking_estado USING btree (id_etapa);


--
-- Name: idx_34aefc66ff707141; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_34aefc66ff707141 ON sifda_tracking_estado USING btree (id_orden_trabajo);


--
-- Name: idx_68d54b24d23f6540; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_68d54b24d23f6540 ON sifda_reprogramacion_servicio USING btree (id_solicitud_servicio);


--
-- Name: idx_789f9e17d23f6540; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_789f9e17d23f6540 ON sifda_detalle_solicitud_servicio USING btree (id_solicitud_servicio);


--
-- Name: idx_7fe39de2828363b6; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_7fe39de2828363b6 ON sifda_tipo_recurso_dependencia USING btree (id_tipo_recurso);


--
-- Name: idx_7fe39de2e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_7fe39de2e54d836e ON sifda_tipo_recurso_dependencia USING btree (id_dependencia_establecimiento);


--
-- Name: idx_9087fef961b1bee8; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9087fef961b1bee8 ON bitacora USING btree (id_evento);


--
-- Name: idx_9087fef9a76ed395; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9087fef9a76ed395 ON bitacora USING btree (user_id);


--
-- Name: idx_9b784dd9890253c7; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9b784dd9890253c7 ON sidpla_subactividad USING btree (id_empleado);


--
-- Name: idx_9b784dd9dc70121; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9b784dd9dc70121 ON sidpla_subactividad USING btree (id_actividad);


--
-- Name: idx_9e3a7e98653f909; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9e3a7e98653f909 ON sidpla_actividad USING btree (id_linea_estrategica);


--
-- Name: idx_9e3a7e98890253c7; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_9e3a7e98890253c7 ON sidpla_actividad USING btree (id_empleado);


--
-- Name: idx_b0295384d56b1641; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b0295384d56b1641 ON ctl_empleado USING btree (id_cargo);


--
-- Name: idx_b0295384e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b0295384e54d836e ON ctl_empleado USING btree (id_dependencia_establecimiento);


--
-- Name: idx_b3c77447a76ed395; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b3c77447a76ed395 ON fos_user_user_group USING btree (user_id);


--
-- Name: idx_b3c77447fe54d947; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_b3c77447fe54d947 ON fos_user_user_group USING btree (group_id);


--
-- Name: idx_bc5984322bf76b46; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_bc5984322bf76b46 ON ctl_dependencia_establecimiento USING btree (id_dependencia_padre);


--
-- Name: idx_bc5984327dfa12f6; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_bc5984327dfa12f6 ON ctl_dependencia_establecimiento USING btree (id_establecimiento);


--
-- Name: idx_bc598432ca22cd3f; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_bc598432ca22cd3f ON ctl_dependencia_establecimiento USING btree (id_dependencia);


--
-- Name: idx_bf94104adc70121; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_bf94104adc70121 ON sifda_tipo_servicio USING btree (id_actividad);


--
-- Name: idx_bf94104ae54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_bf94104ae54d836e ON sifda_tipo_servicio USING btree (id_dependencia_establecimiento);


--
-- Name: idx_bitacora; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_bitacora ON bitacora USING btree (id);


--
-- Name: idx_c560d761890253c7; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_c560d761890253c7 ON fos_user_user USING btree (id_empleado);


--
-- Name: idx_c560d761e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_c560d761e54d836e ON fos_user_user USING btree (id_dependencia_establecimiento);


--
-- Name: idx_catalogo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_catalogo ON catalogo USING btree (id);


--
-- Name: idx_ctl_cargo; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_cargo ON ctl_cargo USING btree (id);


--
-- Name: idx_ctl_tipo_dependencia; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_ctl_tipo_dependencia ON ctl_tipo_dependencia USING btree (id);


--
-- Name: idx_d0a819c8ff707141; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_d0a819c8ff707141 ON sifda_retraso_actividad USING btree (id_orden_trabajo);


--
-- Name: idx_det_solic_serv; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_det_solic_serv ON sifda_detalle_solicitud_orden USING btree (id);


--
-- Name: idx_detalle_solicitud_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_detalle_solicitud_servicio ON sifda_detalle_solicitud_servicio USING btree (id);


--
-- Name: idx_e01bb1a36a540e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e01bb1a36a540e ON sifda_solicitud_servicio USING btree (id_estado);


--
-- Name: idx_e01bb1a3a36b7986; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e01bb1a3a36b7986 ON sifda_solicitud_servicio USING btree (id_tipo_servicio);


--
-- Name: idx_e01bb1a3a76ed395; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e01bb1a3a76ed395 ON sifda_solicitud_servicio USING btree (user_id);


--
-- Name: idx_e01bb1a3e2aa4972; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e01bb1a3e2aa4972 ON sifda_solicitud_servicio USING btree (id_medio_solicita);


--
-- Name: idx_e01bb1a3e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e01bb1a3e54d836e ON sifda_solicitud_servicio USING btree (id_dependencia_establecimiento);


--
-- Name: idx_e38f188d23bdde75; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e38f188d23bdde75 ON sifda_orden_trabajo USING btree (id_prioridad);


--
-- Name: idx_e38f188d6a540e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e38f188d6a540e ON sifda_orden_trabajo USING btree (id_estado);


--
-- Name: idx_e38f188dd23f6540; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e38f188dd23f6540 ON sifda_orden_trabajo USING btree (id_solicitud_servicio);


--
-- Name: idx_e38f188de54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e38f188de54d836e ON sifda_orden_trabajo USING btree (id_dependencia_establecimiento);


--
-- Name: idx_e8978381b77787d0; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_e8978381b77787d0 ON catalogo_detalle USING btree (id_catalogo);


--
-- Name: idx_ea60c340e54d836e; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_ea60c340e54d836e ON sidpla_linea_estrategica USING btree (id_dependencia_establecimiento);


--
-- Name: idx_empleado; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_empleado ON ctl_empleado USING btree (id);


--
-- Name: idx_establecimiento; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_establecimiento ON ctl_establecimiento USING btree (id);


--
-- Name: idx_fcb39128562305e8; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_fcb39128562305e8 ON sifda_recurso_servicio USING btree (id_informe_orden_trabajo);


--
-- Name: idx_fcb391289bf924c0; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX idx_fcb391289bf924c0 ON sifda_recurso_servicio USING btree (id_tipo_recurso_dependencia);


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
-- Name: idx_tracking_estado; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX idx_tracking_estado ON sifda_tracking_estado USING btree (id);


--
-- Name: indx_sifda_tipo_servicio; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE UNIQUE INDEX indx_sifda_tipo_servicio ON sifda_tipo_servicio USING btree (id);


--
-- Name: se_generan_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX se_generan_fk ON sifda_informe_orden_trabajo USING btree (id_etapa);


--
-- Name: tiene_fk; Type: INDEX; Schema: public; Owner: sifda; Tablespace: 
--

CREATE INDEX tiene_fk ON sifda_ruta USING btree (id_tipo_servicio);


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
-- Name: fk_144207df49e7be1; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia
    ADD CONSTRAINT fk_144207df49e7be1 FOREIGN KEY (id_tipo_dependencia) REFERENCES ctl_tipo_dependencia(id);


--
-- Name: fk_1f50e2dc890253c7; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT fk_1f50e2dc890253c7 FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id);


--
-- Name: fk_1f50e2dcff707141; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_equipo_trabajo
    ADD CONSTRAINT fk_1f50e2dcff707141 FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id);


--
-- Name: fk_238434196d903705; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT fk_238434196d903705 FOREIGN KEY (id_detalle_solicitud_servicio) REFERENCES sifda_detalle_solicitud_servicio(id);


--
-- Name: fk_23843419ff707141; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_orden
    ADD CONSTRAINT fk_23843419ff707141 FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id);


--
-- Name: fk_24d08fe93a5c634d; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_24d08fe93a5c634d FOREIGN KEY (id_subactividad) REFERENCES sidpla_subactividad(id);


--
-- Name: fk_24d08fe9890253c7; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_24d08fe9890253c7 FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id);


--
-- Name: fk_24d08fe9e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_24d08fe9e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_24d08fe9ff707141; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_informe_orden_trabajo
    ADD CONSTRAINT fk_24d08fe9ff707141 FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id);


--
-- Name: fk_26b328fea36b7986; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT fk_26b328fea36b7986 FOREIGN KEY (id_tipo_servicio) REFERENCES sifda_tipo_servicio(id);


--
-- Name: fk_26b328fec1b7f0f4; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_ruta_ciclo_vida
    ADD CONSTRAINT fk_26b328fec1b7f0f4 FOREIGN KEY (id_etapa) REFERENCES sifda_ruta_ciclo_vida(id);


--
-- Name: fk_34aefc666a540e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_34aefc666a540e FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id);


--
-- Name: fk_34aefc66c1b7f0f4; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_34aefc66c1b7f0f4 FOREIGN KEY (id_etapa) REFERENCES sifda_ruta_ciclo_vida(id);


--
-- Name: fk_34aefc66ff707141; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tracking_estado
    ADD CONSTRAINT fk_34aefc66ff707141 FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id);


--
-- Name: fk_68d54b24d23f6540; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_reprogramacion_servicio
    ADD CONSTRAINT fk_68d54b24d23f6540 FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id);


--
-- Name: fk_789f9e17d23f6540; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_detalle_solicitud_servicio
    ADD CONSTRAINT fk_789f9e17d23f6540 FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id);


--
-- Name: fk_7fe39de2828363b6; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT fk_7fe39de2828363b6 FOREIGN KEY (id_tipo_recurso) REFERENCES sifda_tipo_recurso(id);


--
-- Name: fk_7fe39de2e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_recurso_dependencia
    ADD CONSTRAINT fk_7fe39de2e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_9087fef961b1bee8; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT fk_9087fef961b1bee8 FOREIGN KEY (id_evento) REFERENCES catalogo_detalle(id);


--
-- Name: fk_9087fef9a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY bitacora
    ADD CONSTRAINT fk_9087fef9a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user_user(id);


--
-- Name: fk_9b784dd9890253c7; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT fk_9b784dd9890253c7 FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id);


--
-- Name: fk_9b784dd9dc70121; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_subactividad
    ADD CONSTRAINT fk_9b784dd9dc70121 FOREIGN KEY (id_actividad) REFERENCES sidpla_actividad(id);


--
-- Name: fk_9e3a7e98653f909; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT fk_9e3a7e98653f909 FOREIGN KEY (id_linea_estrategica) REFERENCES sidpla_linea_estrategica(id);


--
-- Name: fk_9e3a7e98890253c7; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_actividad
    ADD CONSTRAINT fk_9e3a7e98890253c7 FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id);


--
-- Name: fk_b0295384d56b1641; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT fk_b0295384d56b1641 FOREIGN KEY (id_cargo) REFERENCES ctl_cargo(id);


--
-- Name: fk_b0295384e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_empleado
    ADD CONSTRAINT fk_b0295384e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


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
-- Name: fk_bc5984322bf76b46; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_bc5984322bf76b46 FOREIGN KEY (id_dependencia_padre) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_bc5984327dfa12f6; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_bc5984327dfa12f6 FOREIGN KEY (id_establecimiento) REFERENCES ctl_establecimiento(id);


--
-- Name: fk_bc598432ca22cd3f; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY ctl_dependencia_establecimiento
    ADD CONSTRAINT fk_bc598432ca22cd3f FOREIGN KEY (id_dependencia) REFERENCES ctl_dependencia(id);


--
-- Name: fk_bf94104adc70121; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT fk_bf94104adc70121 FOREIGN KEY (id_actividad) REFERENCES sidpla_actividad(id);


--
-- Name: fk_bf94104ae54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_tipo_servicio
    ADD CONSTRAINT fk_bf94104ae54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_c560d761890253c7; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user
    ADD CONSTRAINT fk_c560d761890253c7 FOREIGN KEY (id_empleado) REFERENCES ctl_empleado(id);


--
-- Name: fk_c560d761e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY fos_user_user
    ADD CONSTRAINT fk_c560d761e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_d0a819c8ff707141; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_retraso_actividad
    ADD CONSTRAINT fk_d0a819c8ff707141 FOREIGN KEY (id_orden_trabajo) REFERENCES sifda_orden_trabajo(id);


--
-- Name: fk_e01bb1a36a540e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a36a540e FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id);


--
-- Name: fk_e01bb1a3a36b7986; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a3a36b7986 FOREIGN KEY (id_tipo_servicio) REFERENCES sifda_tipo_servicio(id);


--
-- Name: fk_e01bb1a3a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a3a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user_user(id);


--
-- Name: fk_e01bb1a3e2aa4972; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a3e2aa4972 FOREIGN KEY (id_medio_solicita) REFERENCES catalogo_detalle(id);


--
-- Name: fk_e01bb1a3e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_solicitud_servicio
    ADD CONSTRAINT fk_e01bb1a3e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_e38f188d23bdde75; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_e38f188d23bdde75 FOREIGN KEY (id_prioridad) REFERENCES catalogo_detalle(id);


--
-- Name: fk_e38f188d6a540e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_e38f188d6a540e FOREIGN KEY (id_estado) REFERENCES catalogo_detalle(id);


--
-- Name: fk_e38f188dd23f6540; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_e38f188dd23f6540 FOREIGN KEY (id_solicitud_servicio) REFERENCES sifda_solicitud_servicio(id);


--
-- Name: fk_e38f188de54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_orden_trabajo
    ADD CONSTRAINT fk_e38f188de54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_e8978381b77787d0; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY catalogo_detalle
    ADD CONSTRAINT fk_e8978381b77787d0 FOREIGN KEY (id_catalogo) REFERENCES catalogo(id);


--
-- Name: fk_ea60c340e54d836e; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sidpla_linea_estrategica
    ADD CONSTRAINT fk_ea60c340e54d836e FOREIGN KEY (id_dependencia_establecimiento) REFERENCES ctl_dependencia_establecimiento(id);


--
-- Name: fk_fcb39128562305e8; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT fk_fcb39128562305e8 FOREIGN KEY (id_informe_orden_trabajo) REFERENCES sifda_informe_orden_trabajo(id);


--
-- Name: fk_fcb391289bf924c0; Type: FK CONSTRAINT; Schema: public; Owner: sifda
--

ALTER TABLE ONLY sifda_recurso_servicio
    ADD CONSTRAINT fk_fcb391289bf924c0 FOREIGN KEY (id_tipo_recurso_dependencia) REFERENCES sifda_tipo_recurso_dependencia(id);


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
