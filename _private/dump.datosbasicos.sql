--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: auditoria; Type: SCHEMA; Schema: -; Owner: carbonara
--

CREATE SCHEMA auditoria;


ALTER SCHEMA auditoria OWNER TO carbonara;

--
-- Name: SCHEMA auditoria; Type: COMMENT; Schema: -; Owner: carbonara
--

COMMENT ON SCHEMA auditoria IS 'Esquema que sólo será utilizado internamente por la BD para registrar el histórico de los movimientos';


--
-- Name: cc1; Type: SCHEMA; Schema: -; Owner: carbonara
--

CREATE SCHEMA cc1;


ALTER SCHEMA cc1 OWNER TO carbonara;

--
-- Name: SCHEMA cc1; Type: COMMENT; Schema: -; Owner: carbonara
--

COMMENT ON SCHEMA cc1 IS 'Consejo Comunal Dr. Ramón Parra Picón. Pedregosa Alta';


--
-- Name: encuestas; Type: SCHEMA; Schema: -; Owner: carbonara
--

CREATE SCHEMA encuestas;


ALTER SCHEMA encuestas OWNER TO carbonara;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: carbonara
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO carbonara;

SET search_path = public, pg_catalog;

--
-- Name: auditoria(); Type: FUNCTION; Schema: public; Owner: carbonara
--

CREATE FUNCTION auditoria() RETURNS trigger
    AS $$BEGIN

    IF (TG_OP = 'DELETE') THEN

            INSERT INTO public.auditor (id, op, tbl, usr, ip) VALUES ((SELECT id FROM audit_tmp ORDER BY ocurrencia LIMIT 1),TG_OP,TG_RELNAME,(SELECT usuario FROM audit_tmp ORDER BY ocurrencia LIMIT 1),(SELECT ip FROM audit_tmp ORDER BY ocurrencia LIMIT 1));

            RETURN OLD;

        ELSIF (TG_OP = 'UPDATE') THEN

            INSERT INTO public.auditor (id, op, tbl, usr, ip) VALUES ((SELECT id FROM audit_tmp ORDER BY ocurrencia LIMIT 1),TG_OP,TG_RELNAME,(SELECT usuario FROM audit_tmp ORDER BY ocurrencia LIMIT 1),(SELECT ip FROM audit_tmp ORDER BY ocurrencia LIMIT 1));

            RETURN NEW;

        ELSIF (TG_OP = 'INSERT') THEN

            INSERT INTO public.auditor (id, op, tbl, usr, ip) VALUES ((SELECT id FROM audit_tmp ORDER BY ocurrencia LIMIT 1),TG_OP,TG_RELNAME,(SELECT usuario FROM audit_tmp ORDER BY ocurrencia LIMIT 1),(SELECT ip FROM audit_tmp ORDER BY ocurrencia LIMIT 1));

            RETURN NEW;

        END IF;

END;

$$
    LANGUAGE plpgsql;


ALTER FUNCTION public.auditoria() OWNER TO carbonara;

SET search_path = auditoria, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: historico; Type: TABLE; Schema: auditoria; Owner: carbonara; Tablespace:
--

CREATE TABLE historico (
    usuario integer NOT NULL,
    fecha date DEFAULT now() NOT NULL,
    id_new integer,
    id_old integer,
    tabla character varying NOT NULL
);


ALTER TABLE auditoria.historico OWNER TO carbonara;

--
-- Name: COLUMN historico.usuario; Type: COMMENT; Schema: auditoria; Owner: carbonara
--

COMMENT ON COLUMN historico.usuario IS 'Usuario que realiza la operación';


--
-- Name: COLUMN historico.fecha; Type: COMMENT; Schema: auditoria; Owner: carbonara
--

COMMENT ON COLUMN historico.fecha IS 'Momento en el que se realiza la operación';


--
-- Name: COLUMN historico.id_new; Type: COMMENT; Schema: auditoria; Owner: carbonara
--

COMMENT ON COLUMN historico.id_new IS 'En caso de ser una inserción o actualización, el valor del identificador';


--
-- Name: COLUMN historico.id_old; Type: COMMENT; Schema: auditoria; Owner: carbonara
--

COMMENT ON COLUMN historico.id_old IS 'En caso de ser una modificación o eliminación, el valor del identificador';


--
-- Name: COLUMN historico.tabla; Type: COMMENT; Schema: auditoria; Owner: carbonara
--

COMMENT ON COLUMN historico.tabla IS 'Tabla sobre la cual se realiza la operación';


SET search_path = cc1, pg_catalog;

--
-- Name: crear_usuarios; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE crear_usuarios (
    cedula character varying(9) NOT NULL,
    nombre character varying(30) NOT NULL,
    apellido character varying(30) NOT NULL,
    usuario character varying(20) NOT NULL,
    clave character varying(250) NOT NULL,
    fregistro date DEFAULT ('now'::text)::date NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.crear_usuarios OWNER TO carbonara;

--
-- Name: TABLE crear_usuarios; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE crear_usuarios IS 'Tabla para que los usuarios que haran las pruebas del sistema';


--
-- Name: COLUMN crear_usuarios.fregistro; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN crear_usuarios.fregistro IS 'Feche en que se registra el usuario';


--
-- Name: crear_usuarios_cedula_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE crear_usuarios_cedula_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.crear_usuarios_cedula_seq OWNER TO carbonara;

--
-- Name: crear_usuarios_cedula_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('crear_usuarios_cedula_seq', 62, true);


--
-- Name: menu; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE menu (
    id integer NOT NULL,
    mascara character varying(50) NOT NULL,
    etiqueta character varying(50),
    id_padre integer,
    mascara_padre character varying(50),
    etiqueta_padre character varying(50),
    posicion smallint,
    posicion_padre smallint,
    visible boolean,
    nivel_acceso smallint,
    accion character varying(100),
    param1 text,
    menu character varying,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    usr integer NOT NULL,
    ip inet NOT NULL
);


ALTER TABLE cc1.menu OWNER TO carbonara;

--
-- Name: COLUMN menu.usr; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN menu.usr IS 'Usuario que realiza la transaccción';


--
-- Name: COLUMN menu.ip; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN menu.ip IS 'Dirección IP desde donde se realiza la transacción';


--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE menu_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.menu_id_seq OWNER TO carbonara;

--
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE menu_id_seq OWNED BY menu.id;


--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('menu_id_seq', 101, true);


--
-- Name: tabla0; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla0 (
    tabla1_campo1 integer NOT NULL,
    tabla1_campo2 character varying(30),
    tabla1_campo3 character varying(30),
    tabla1_campo4 character varying(30) NOT NULL,
    tabla1_campo5 character varying(30) NOT NULL
);


ALTER TABLE cc1.tabla0 OWNER TO carbonara;

--
-- Name: TABLE tabla0; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla0 IS 'censo';


--
-- Name: COLUMN tabla0.tabla1_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla0.tabla1_campo1 IS 'identificador único. puede servir comor contador';


--
-- Name: COLUMN tabla0.tabla1_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla0.tabla1_campo2 IS 'nombre';


--
-- Name: tabla1; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla1 (
    tabla1_campo1 integer NOT NULL,
    tabla1_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla1_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla1 OWNER TO carbonara;

--
-- Name: TABLE tabla1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla1 IS 'Grado de Instrucción';


--
-- Name: COLUMN tabla1.tabla1_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla1.tabla1_campo1 IS 'identificador';


--
-- Name: COLUMN tabla1.tabla1_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla1.tabla1_campo2 IS 'descripción';


--
-- Name: COLUMN tabla1.tabla1_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla1.tabla1_campo3 IS 'Visible';


--
-- Name: tabla10; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla10 (
    tabla10_campo1 integer NOT NULL,
    tabla10_campo2 character varying(50) NOT NULL,
    tabla10_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla10 OWNER TO carbonara;

--
-- Name: TABLE tabla10; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla10 IS 'Tenencia de la vivienda';


--
-- Name: COLUMN tabla10.tabla10_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla10.tabla10_campo1 IS 'identificador';


--
-- Name: COLUMN tabla10.tabla10_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla10.tabla10_campo2 IS 'descripción';


--
-- Name: COLUMN tabla10.tabla10_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla10.tabla10_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla10_tabla10_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla10_tabla10_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla10_tabla10_campo1_seq OWNER TO carbonara;

--
-- Name: tabla10_tabla10_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla10_tabla10_campo1_seq OWNED BY tabla10.tabla10_campo1;


--
-- Name: tabla10_tabla10_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla10_tabla10_campo1_seq', 4, true);


--
-- Name: tabla11; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla11 (
    tabla11_campo1 integer NOT NULL,
    tabla11_campo2 character varying(50) NOT NULL,
    tabla11_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla11 OWNER TO carbonara;

--
-- Name: TABLE tabla11; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla11 IS 'Material predominante de las paredes exteriores';


--
-- Name: COLUMN tabla11.tabla11_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla11.tabla11_campo1 IS 'identificador';


--
-- Name: COLUMN tabla11.tabla11_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla11.tabla11_campo2 IS 'descripción';


--
-- Name: COLUMN tabla11.tabla11_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla11.tabla11_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla11_tabla11_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla11_tabla11_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla11_tabla11_campo1_seq OWNER TO carbonara;

--
-- Name: tabla11_tabla11_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla11_tabla11_campo1_seq OWNED BY tabla11.tabla11_campo1;


--
-- Name: tabla11_tabla11_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla11_tabla11_campo1_seq', 2, true);


--
-- Name: tabla12; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla12 (
    tabla12_campo1 integer NOT NULL,
    tabla12_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    tabla12_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla12 OWNER TO carbonara;

--
-- Name: TABLE tabla12; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla12 IS 'Comité';


--
-- Name: COLUMN tabla12.tabla12_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla12.tabla12_campo1 IS 'identificador';


--
-- Name: COLUMN tabla12.tabla12_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla12.tabla12_campo2 IS 'descripción';


--
-- Name: COLUMN tabla12.fregistro; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla12.fregistro IS 'fecha de registro';


--
-- Name: COLUMN tabla12.tabla12_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla12.tabla12_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla12_tabla12_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla12_tabla12_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla12_tabla12_campo1_seq OWNER TO carbonara;

--
-- Name: tabla12_tabla12_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla12_tabla12_campo1_seq OWNED BY tabla12.tabla12_campo1;


--
-- Name: tabla12_tabla12_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla12_tabla12_campo1_seq', 42, true);


--
-- Name: tabla13; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla13 (
    tabla13_campo1 integer NOT NULL,
    tabla13_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla13_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla13 OWNER TO carbonara;

--
-- Name: TABLE tabla13; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla13 IS 'Sector donde vive';


--
-- Name: COLUMN tabla13.tabla13_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla13.tabla13_campo1 IS 'identificador';


--
-- Name: COLUMN tabla13.tabla13_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla13.tabla13_campo2 IS 'descripción';


--
-- Name: COLUMN tabla13.tabla13_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla13.tabla13_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla13_tabla13_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla13_tabla13_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla13_tabla13_campo1_seq OWNER TO carbonara;

--
-- Name: tabla13_tabla13_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla13_tabla13_campo1_seq OWNED BY tabla13.tabla13_campo1;


--
-- Name: tabla13_tabla13_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla13_tabla13_campo1_seq', 8, true);


--
-- Name: tabla14; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla14 (
    tabla14_campo1 integer NOT NULL,
    tabla14_campo2 integer NOT NULL,
    tabla14_campo3 character varying(50) NOT NULL,
    tabla14_campo4 numeric(20,18) NOT NULL,
    tabla14_campo5 numeric(20,18) NOT NULL,
    tabla14_campo6 date DEFAULT now() NOT NULL,
    tabla14_campo7 boolean DEFAULT true NOT NULL,
    tabla7_campo1 integer DEFAULT 0 NOT NULL,
    tabla10_campo1 integer DEFAULT 0 NOT NULL,
    tabla8_campo1 integer DEFAULT 0 NOT NULL,
    tabla9_campo1 integer DEFAULT 0 NOT NULL,
    tabla11_campo1 integer DEFAULT 0 NOT NULL,
    tabla14_campo8 smallint,
    tabla14_campo9 boolean,
    tabla14_campo10 boolean,
    tabla14_campo11 smallint,
    tabla14_campo12 boolean,
    tabla14_campo13 text,
    tabla15_campo1 integer,
    tabla14_campo14 character varying(100),
    tabla14_campo15 character varying(6)
);


ALTER TABLE cc1.tabla14 OWNER TO carbonara;

--
-- Name: TABLE tabla14; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla14 IS 'Vivienda: Ubicación y georeferenciación';


--
-- Name: COLUMN tabla14.tabla14_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo1 IS 'identificador';


--
-- Name: COLUMN tabla14.tabla14_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo2 IS 'Sector';


--
-- Name: COLUMN tabla14.tabla14_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo3 IS 'Nombre de la vivienda';


--
-- Name: COLUMN tabla14.tabla14_campo4; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo4 IS 'Latitud';


--
-- Name: COLUMN tabla14.tabla14_campo5; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo5 IS 'Longitud';


--
-- Name: COLUMN tabla14.tabla14_campo6; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo6 IS 'Fecha de registro';


--
-- Name: COLUMN tabla14.tabla14_campo7; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo7 IS 'Activo';


--
-- Name: COLUMN tabla14.tabla7_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla7_campo1 IS 'Tipo de Vivienda';


--
-- Name: COLUMN tabla14.tabla10_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla10_campo1 IS 'Tenencia de la Vivienda';


--
-- Name: COLUMN tabla14.tabla8_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla8_campo1 IS 'Material predominante del piso';


--
-- Name: COLUMN tabla14.tabla9_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla9_campo1 IS 'Material predominante del techo';


--
-- Name: COLUMN tabla14.tabla11_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla11_campo1 IS 'Material predominante de las paredes exteriores';


--
-- Name: COLUMN tabla14.tabla14_campo8; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo8 IS 'Número de habitaciones';


--
-- Name: COLUMN tabla14.tabla14_campo9; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo9 IS 'Posee Sala';


--
-- Name: COLUMN tabla14.tabla14_campo10; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo10 IS 'Posee cocina';


--
-- Name: COLUMN tabla14.tabla14_campo11; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo11 IS 'Número de grupos familiares que residen en la vivienda';


--
-- Name: COLUMN tabla14.tabla14_campo12; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo12 IS 'Requiere de algún arreglo o modificación';


--
-- Name: COLUMN tabla14.tabla14_campo13; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo13 IS 'Explicar arreglo o modificación';


--
-- Name: COLUMN tabla14.tabla15_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla15_campo1 IS 'Disposición de aguas servidas';


--
-- Name: COLUMN tabla14.tabla14_campo14; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo14 IS 'Calle o Avenida';


--
-- Name: COLUMN tabla14.tabla14_campo15; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla14.tabla14_campo15 IS 'Número de la vivienda';


--
-- Name: tabla14_tabla14_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla14_tabla14_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla14_tabla14_campo1_seq OWNER TO carbonara;

--
-- Name: tabla14_tabla14_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla14_tabla14_campo1_seq OWNED BY tabla14.tabla14_campo1;


--
-- Name: tabla14_tabla14_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla14_tabla14_campo1_seq', 15, true);


--
-- Name: tabla15; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla15 (
    tabla15_campo1 integer NOT NULL,
    tabla15_campo2 character varying(50) NOT NULL,
    tabla15_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla15 OWNER TO carbonara;

--
-- Name: TABLE tabla15; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla15 IS 'Disposición de aguas servidas';


--
-- Name: COLUMN tabla15.tabla15_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla15.tabla15_campo1 IS 'identificador';


--
-- Name: COLUMN tabla15.tabla15_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla15.tabla15_campo2 IS 'descripción';


--
-- Name: COLUMN tabla15.tabla15_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla15.tabla15_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla15_tabla15_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla15_tabla15_campo1_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla15_tabla15_campo1_seq OWNER TO carbonara;

--
-- Name: tabla15_tabla15_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla15_tabla15_campo1_seq OWNED BY tabla15.tabla15_campo1;


--
-- Name: tabla15_tabla15_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla15_tabla15_campo1_seq', 1, false);


--
-- Name: tabla16; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla16 (
    tabla16_campo1 integer NOT NULL,
    tabla16_campo2 character varying(50) NOT NULL,
    tabla16_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla16 OWNER TO carbonara;

--
-- Name: TABLE tabla16; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla16 IS 'Servicios básicos';


--
-- Name: COLUMN tabla16.tabla16_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla16.tabla16_campo1 IS 'identificador';


--
-- Name: COLUMN tabla16.tabla16_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla16.tabla16_campo2 IS 'descripción';


--
-- Name: COLUMN tabla16.tabla16_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla16.tabla16_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla16_tabla16_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla16_tabla16_campo1_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla16_tabla16_campo1_seq OWNER TO carbonara;

--
-- Name: tabla16_tabla16_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla16_tabla16_campo1_seq OWNED BY tabla16.tabla16_campo1;


--
-- Name: tabla16_tabla16_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla16_tabla16_campo1_seq', 1, false);


--
-- Name: tabla17; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla17 (
    tabla17_campo1 integer NOT NULL,
    tabla20_campo2 character varying(9) NOT NULL,
    tabla12_campo1 integer NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    tabla17_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla17 OWNER TO carbonara;

--
-- Name: TABLE tabla17; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla17 IS 'Voceros de Unidades';


--
-- Name: COLUMN tabla17.tabla17_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla17.tabla17_campo1 IS 'identificador';


--
-- Name: COLUMN tabla17.tabla20_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla17.tabla20_campo2 IS 'Cédula. Debe ser habitante de la comunidad';


--
-- Name: COLUMN tabla17.tabla12_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla17.tabla12_campo1 IS 'Comité o Unidad';


--
-- Name: COLUMN tabla17.fregistro; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla17.fregistro IS 'fecha de registro';


--
-- Name: COLUMN tabla17.tabla17_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla17.tabla17_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla17_tabla17_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla17_tabla17_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla17_tabla17_campo1_seq OWNER TO carbonara;

--
-- Name: tabla17_tabla17_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla17_tabla17_campo1_seq OWNED BY tabla17.tabla17_campo1;


--
-- Name: tabla17_tabla17_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla17_tabla17_campo1_seq', 24, true);


--
-- Name: tabla18; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla18 (
    tabla18_campo1 integer NOT NULL,
    tabla18_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla18_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla18 OWNER TO carbonara;

--
-- Name: TABLE tabla18; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla18 IS 'Situación Laboral';


--
-- Name: COLUMN tabla18.tabla18_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla18.tabla18_campo1 IS 'identificador';


--
-- Name: COLUMN tabla18.tabla18_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla18.tabla18_campo2 IS 'descripción';


--
-- Name: COLUMN tabla18.tabla18_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla18.tabla18_campo3 IS 'Visible';


--
-- Name: tabla18_campo1_seq1; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla18_campo1_seq1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla18_campo1_seq1 OWNER TO carbonara;

--
-- Name: tabla18_campo1_seq1; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla18_campo1_seq1 OWNED BY tabla18.tabla18_campo1;


--
-- Name: tabla18_campo1_seq1; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla18_campo1_seq1', 6, true);


--
-- Name: tabla18_tabla18_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla18_tabla18_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla18_tabla18_campo1_seq OWNER TO carbonara;

--
-- Name: tabla18_tabla18_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla18_tabla18_campo1_seq', 4, true);


--
-- Name: tabla19; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla19 (
    tabla19_campo1 integer NOT NULL,
    tabla19_campo2 character varying(50) NOT NULL,
    tabla19_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla19 OWNER TO carbonara;

--
-- Name: TABLE tabla19; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla19 IS 'Misiones';


--
-- Name: COLUMN tabla19.tabla19_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla19.tabla19_campo1 IS 'identificador';


--
-- Name: COLUMN tabla19.tabla19_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla19.tabla19_campo2 IS 'descripción';


--
-- Name: COLUMN tabla19.tabla19_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla19.tabla19_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla19_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla19_campo1_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla19_campo1_seq OWNER TO carbonara;

--
-- Name: tabla19_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla19_campo1_seq OWNED BY tabla19.tabla19_campo1;


--
-- Name: tabla19_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla19_campo1_seq', 1, false);


--
-- Name: tabla1_tabla1_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla1_tabla1_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla1_tabla1_campo1_seq OWNER TO carbonara;

--
-- Name: tabla1_tabla1_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla1_tabla1_campo1_seq OWNED BY tabla0.tabla1_campo1;


--
-- Name: tabla1_tabla1_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla1_tabla1_campo1_seq', 1, true);


--
-- Name: tabla1_tabla1_campo1_seq1; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla1_tabla1_campo1_seq1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla1_tabla1_campo1_seq1 OWNER TO carbonara;

--
-- Name: tabla1_tabla1_campo1_seq1; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla1_tabla1_campo1_seq1 OWNED BY tabla1.tabla1_campo1;


--
-- Name: tabla1_tabla1_campo1_seq1; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla1_tabla1_campo1_seq1', 14, true);


--
-- Name: tabla2; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla2 (
    tabla2_campo1 integer NOT NULL,
    tabla2_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla2_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla2 OWNER TO carbonara;

--
-- Name: TABLE tabla2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla2 IS 'Ocupación';


--
-- Name: COLUMN tabla2.tabla2_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla2.tabla2_campo1 IS 'identificador';


--
-- Name: COLUMN tabla2.tabla2_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla2.tabla2_campo2 IS 'descripción';


--
-- Name: COLUMN tabla2.tabla2_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla2.tabla2_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla20; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla20 (
    tabla20_campo1 integer NOT NULL,
    tabla20_campo2 character varying(9) NOT NULL,
    tabla20_campo3 character varying(30) NOT NULL,
    tabla20_campo4 character varying(30) NOT NULL,
    tabla20_campo5 smallint,
    tabla20_campo6 date,
    tabla20_campo7 character varying(1) NOT NULL,
    tabla20_campo8 character varying(1),
    id_sector integer,
    id_ocupacion integer,
    id_profesion integer,
    id_parentesco integer,
    tabla20_campo11 date DEFAULT now() NOT NULL,
    tabla20_campo12 boolean DEFAULT true NOT NULL,
    tabla20_campo9 character varying(1) DEFAULT 0 NOT NULL,
    tabla20_campo10 boolean DEFAULT false NOT NULL,
    tabla20_campo13 boolean DEFAULT false NOT NULL,
    tabla20_campo14 character varying(40),
    tabla20_campo15 boolean,
    tabla20_campo16 character varying(250),
    tabla20_campo17 boolean,
    tabla20_campo18 boolean,
    tabla20_campo19 boolean,
    tabla20_campo20 boolean DEFAULT false,
    tabla20_campo21 integer,
    tabla20_campo22 boolean DEFAULT false,
    tabla20_campo23 text,
    tabla20_campo24 boolean DEFAULT false,
    tabla20_campo25 text,
    tabla20_campo26 boolean DEFAULT false,
    tabla20_campo27 text,
    tabla20_campo28 boolean DEFAULT false,
    tabla20_campo29 text,
    tabla20_campo30 boolean DEFAULT false NOT NULL,
    tabla20_campo31 boolean DEFAULT false,
    tabla20_campo32 boolean DEFAULT false,
    tabla20_campo33 text,
    tabla20_campo34 boolean DEFAULT false,
    tabla20_campo35 character varying(250),
    tabla20_campo36 character varying(250),
    tabla20_campo37 boolean DEFAULT false,
    tabla20_campo38 character varying(250),
    tabla20_campo39 boolean DEFAULT false,
    tabla20_campo40 character varying(250),
    tabla20_campo41 boolean DEFAULT false,
    tabla20_campo42 character varying(250),
    tabla20_campo43 boolean DEFAULT false,
    tabla20_campo44 character varying(250),
    tabla20_campo45 character varying(250),
    tabla20_campo46 character varying(250),
    tabla20_campo47 character varying(250),
    tabla20_campo48 boolean DEFAULT false,
    tabla20_campo49 character varying(250),
    tabla20_campo50 boolean DEFAULT false,
    tabla20_campo51 character varying(250),
    tabla20_campo52 boolean DEFAULT false,
    tabla20_campo53 character varying(250),
    tabla20_campo54 boolean DEFAULT false,
    tabla20_campo55 integer,
    tabla20_campo56 boolean DEFAULT false,
    tabla20_campo57 integer,
    tabla20_campo58 boolean DEFAULT false,
    tabla20_campo59 boolean DEFAULT false,
    tabla20_campo60 boolean DEFAULT false,
    tabla20_campo61 boolean DEFAULT false,
    tabla20_campo62 boolean DEFAULT false,
    tabla20_campo64 integer,
    tabla20_campo65 boolean DEFAULT false,
    tabla20_campo66 boolean DEFAULT false,
    tabla20_campo67 integer,
    tabla20_campo68 boolean DEFAULT false,
    tabla20_campo69 character varying(250)
);


ALTER TABLE cc1.tabla20 OWNER TO carbonara;

--
-- Name: TABLE tabla20; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla20 IS 'Registro de los habitantes del sector, residenciados y censados en el Consejo Comunal';


--
-- Name: COLUMN tabla20.tabla20_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo2 IS 'Cédula de Identidad';


--
-- Name: COLUMN tabla20.tabla20_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo3 IS 'Nombre(s)';


--
-- Name: COLUMN tabla20.tabla20_campo4; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo4 IS 'Apellido(s)';


--
-- Name: COLUMN tabla20.tabla20_campo5; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo5 IS 'Edad';


--
-- Name: COLUMN tabla20.tabla20_campo6; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo6 IS 'Fecha de Nacimiento';


--
-- Name: COLUMN tabla20.tabla20_campo7; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo7 IS 'Sexo o genero';


--
-- Name: COLUMN tabla20.tabla20_campo8; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo8 IS 'Grado de Instrucción';


--
-- Name: COLUMN tabla20.tabla20_campo9; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo9 IS 'Sn Documentos de identifiación (cédula, partida de nacimiento, etc)';


--
-- Name: COLUMN tabla20.tabla20_campo10; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo10 IS 'Estudia actualmente';


--
-- Name: COLUMN tabla20.tabla20_campo13; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo13 IS 'Trabaja actualmente';


--
-- Name: COLUMN tabla20.tabla20_campo14; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo14 IS 'Correo Electrónico';


--
-- Name: COLUMN tabla20.tabla20_campo15; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo15 IS 'Posee algún terreno';


--
-- Name: COLUMN tabla20.tabla20_campo16; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo16 IS 'Lugar del terreno que posee';


--
-- Name: COLUMN tabla20.tabla20_campo17; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo17 IS 'Tiene Ley de Política Habitacional';


--
-- Name: COLUMN tabla20.tabla20_campo18; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo18 IS 'Tiene Seguro';


--
-- Name: COLUMN tabla20.tabla20_campo19; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo19 IS 'Tiene vehículo';


--
-- Name: COLUMN tabla20.tabla20_campo20; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo20 IS 'Padece de alguna enfermedad';


--
-- Name: COLUMN tabla20.tabla20_campo21; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo21 IS 'Cuál enfermedad padece';


--
-- Name: COLUMN tabla20.tabla20_campo22; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo22 IS 'Requiere tratamiento de por vida';


--
-- Name: COLUMN tabla20.tabla20_campo23; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo23 IS 'Cuál tratamiento de por vida requiere';


--
-- Name: COLUMN tabla20.tabla20_campo24; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo24 IS 'Requiere algún material médico quirúrgico';


--
-- Name: COLUMN tabla20.tabla20_campo25; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo25 IS 'Cuál material médico quirúrgico requiere';


--
-- Name: COLUMN tabla20.tabla20_campo26; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo26 IS 'Requiere de alguna operación';


--
-- Name: COLUMN tabla20.tabla20_campo27; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo27 IS 'Cuál operación requiere';


--
-- Name: COLUMN tabla20.tabla20_campo28; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo28 IS 'Requiere algún servicio de salud';


--
-- Name: COLUMN tabla20.tabla20_campo29; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo29 IS 'Cuál servicio de salud requiere';


--
-- Name: COLUMN tabla20.tabla20_campo30; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo30 IS 'Está embrazada';


--
-- Name: COLUMN tabla20.tabla20_campo31; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo31 IS 'Sabe cómo actuar en caso de una emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo32; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo32 IS 'Conoce los organismos a quien acudir en caso de una emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo33; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo33 IS 'Cuál organismo conoce en caso de una emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo34; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo34 IS 'Conoce los números de emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo35; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo35 IS 'Cuál número de emergencia conoce';


--
-- Name: COLUMN tabla20.tabla20_campo36; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo36 IS 'a dónde se dirigría en caso de una emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo37; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo37 IS 'Ha recibido entrenamiento para casos de emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo38; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo38 IS 'Qué entrenamiento ha recibido para casos de emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo39; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo39 IS 'Le gustaría tomar talleres para casos de emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo40; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo40 IS 'Qué talleres le gustaría tomar para casos de emergencia';


--
-- Name: COLUMN tabla20.tabla20_campo41; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo41 IS 'Posee alguna habilidad artesanal';


--
-- Name: COLUMN tabla20.tabla20_campo42; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo42 IS 'Cuál habilidad artesanal posee';


--
-- Name: COLUMN tabla20.tabla20_campo43; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo43 IS 'Desea integrar algún grupo cultural';


--
-- Name: COLUMN tabla20.tabla20_campo44; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo44 IS 'Cuál grupo cultural desea integrar';


--
-- Name: COLUMN tabla20.tabla20_campo45; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo45 IS 'Qué clase de talleres le gusaría que el INCES dictara en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo46; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo46 IS 'Qué otros talleres le gustaría que se dictaran en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo47; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo47 IS 'Cuál considera que es la necesidad prioritaria de su comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo48; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo48 IS 'Existe algún problema ambiental en su sector';


--
-- Name: COLUMN tabla20.tabla20_campo49; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo49 IS 'cuál problema ambiental existe en su sector';


--
-- Name: COLUMN tabla20.tabla20_campo50; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo50 IS 'Desea que exista un espacio para el esparcimiento en su sector';


--
-- Name: COLUMN tabla20.tabla20_campo51; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo51 IS 'Especifique qué clase de espacio para el esparcimiento';


--
-- Name: COLUMN tabla20.tabla20_campo52; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo52 IS 'Existe algún problema de seguridad en su sector';


--
-- Name: COLUMN tabla20.tabla20_campo53; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo53 IS 'Cuál problema de seguridad existe en su sector';


--
-- Name: COLUMN tabla20.tabla20_campo54; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo54 IS 'Practica algún deporte';


--
-- Name: COLUMN tabla20.tabla20_campo55; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo55 IS 'Qué deporte practica';


--
-- Name: COLUMN tabla20.tabla20_campo56; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo56 IS 'Le gustaría practicar algún deporte';


--
-- Name: COLUMN tabla20.tabla20_campo57; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo57 IS 'Qué deporte le gustaría practicar';


--
-- Name: COLUMN tabla20.tabla20_campo58; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo58 IS 'Percibe problemas de drogas en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo59; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo59 IS 'Percibe problemas de alcoholismo en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo60; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo60 IS 'Percibe problemas de delincuencia en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo61; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo61 IS 'Percibe otro problema en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo62; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo62 IS 'Le gustaría participar en el Consejo Comunal';


--
-- Name: COLUMN tabla20.tabla20_campo64; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo64 IS 'En cuál comité del Consejo Comunal le gustaría participar';


--
-- Name: COLUMN tabla20.tabla20_campo65; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo65 IS 'Sabe sí se está implementando alguna Misión en la comunidad';


--
-- Name: COLUMN tabla20.tabla20_campo66; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo66 IS 'Se beneficia de alguna Misión';


--
-- Name: COLUMN tabla20.tabla20_campo67; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo67 IS 'De cuál Misión se beneficia';


--
-- Name: COLUMN tabla20.tabla20_campo68; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo68 IS 'Desea un crédito Socioproductivo';


--
-- Name: COLUMN tabla20.tabla20_campo69; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla20.tabla20_campo69 IS 'Especcifique para que le gustaría un crédito Socioproductivo';


--
-- Name: tabla20_tabla20_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla20_tabla20_campo1_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla20_tabla20_campo1_seq OWNER TO carbonara;

--
-- Name: tabla20_tabla20_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla20_tabla20_campo1_seq OWNED BY tabla20.tabla20_campo1;


--
-- Name: tabla20_tabla20_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla20_tabla20_campo1_seq', 1, false);


--
-- Name: tabla21; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla21 (
    tabla21_campo1 integer NOT NULL,
    tabla21_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla21_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla21 OWNER TO carbonara;

--
-- Name: TABLE tabla21; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla21 IS 'Salario Aproximado';


--
-- Name: COLUMN tabla21.tabla21_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla21.tabla21_campo1 IS 'identificador';


--
-- Name: COLUMN tabla21.tabla21_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla21.tabla21_campo2 IS 'descripción';


--
-- Name: COLUMN tabla21.tabla21_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla21.tabla21_campo3 IS 'Visible';


--
-- Name: tabla21_campo1_seq1; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla21_campo1_seq1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla21_campo1_seq1 OWNER TO carbonara;

--
-- Name: tabla21_campo1_seq1; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla21_campo1_seq1 OWNED BY tabla21.tabla21_campo1;


--
-- Name: tabla21_campo1_seq1; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla21_campo1_seq1', 3, true);


--
-- Name: tabla22; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla22 (
    tabla22_campo1 integer NOT NULL,
    tabla22_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla22_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla22 OWNER TO carbonara;

--
-- Name: TABLE tabla22; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla22 IS 'Estado Civil';


--
-- Name: COLUMN tabla22.tabla22_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla22.tabla22_campo1 IS 'identificador';


--
-- Name: COLUMN tabla22.tabla22_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla22.tabla22_campo2 IS 'descripción';


--
-- Name: COLUMN tabla22.tabla22_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla22.tabla22_campo3 IS 'Visible';


--
-- Name: tabla22_campo1_seq1; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla22_campo1_seq1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla22_campo1_seq1 OWNER TO carbonara;

--
-- Name: tabla22_campo1_seq1; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla22_campo1_seq1 OWNED BY tabla22.tabla22_campo1;


--
-- Name: tabla22_campo1_seq1; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla22_campo1_seq1', 5, true);


--
-- Name: tabla22_tabla22_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla22_tabla22_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla22_tabla22_campo1_seq OWNER TO carbonara;

--
-- Name: tabla22_tabla22_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla22_tabla22_campo1_seq', 2, true);


--
-- Name: tabla2_id_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla2_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla2_id_seq OWNER TO carbonara;

--
-- Name: tabla2_id_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla2_id_seq', 12, true);


--
-- Name: tabla2_tabla2_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla2_tabla2_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla2_tabla2_campo1_seq OWNER TO carbonara;

--
-- Name: tabla2_tabla2_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla2_tabla2_campo1_seq OWNED BY tabla2.tabla2_campo1;


--
-- Name: tabla2_tabla2_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla2_tabla2_campo1_seq', 64, true);


--
-- Name: tabla3; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla3 (
    tabla3_campo1 integer NOT NULL,
    tabla3_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla3_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla3 OWNER TO carbonara;

--
-- Name: TABLE tabla3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla3 IS 'Profesión';


--
-- Name: COLUMN tabla3.tabla3_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla3.tabla3_campo1 IS 'identificador';


--
-- Name: COLUMN tabla3.tabla3_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla3.tabla3_campo2 IS 'descripción';


--
-- Name: COLUMN tabla3.tabla3_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla3.tabla3_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla3_tabla3_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla3_tabla3_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla3_tabla3_campo1_seq OWNER TO carbonara;

--
-- Name: tabla3_tabla3_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla3_tabla3_campo1_seq OWNED BY tabla3.tabla3_campo1;


--
-- Name: tabla3_tabla3_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla3_tabla3_campo1_seq', 4, true);


--
-- Name: tabla4; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla4 (
    tabla4_campo1 integer NOT NULL,
    tabla4_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla4_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla4 OWNER TO carbonara;

--
-- Name: TABLE tabla4; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla4 IS 'Parentesco con el jefe del hogar';


--
-- Name: COLUMN tabla4.tabla4_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla4.tabla4_campo1 IS 'identificador';


--
-- Name: COLUMN tabla4.tabla4_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla4.tabla4_campo2 IS 'descripción';


--
-- Name: COLUMN tabla4.tabla4_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla4.tabla4_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla4_tabla4_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla4_tabla4_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla4_tabla4_campo1_seq OWNER TO carbonara;

--
-- Name: tabla4_tabla4_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla4_tabla4_campo1_seq OWNED BY tabla4.tabla4_campo1;


--
-- Name: tabla4_tabla4_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla4_tabla4_campo1_seq', 12, true);


--
-- Name: tabla5; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla5 (
    tabla5_campo1 integer NOT NULL,
    tabla5_campo2 character varying(50) NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    tabla5_campo3 boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla5 OWNER TO carbonara;

--
-- Name: TABLE tabla5; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla5 IS 'deportes';


--
-- Name: COLUMN tabla5.tabla5_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla5.tabla5_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla5_tabla5_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla5_tabla5_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla5_tabla5_campo1_seq OWNER TO carbonara;

--
-- Name: tabla5_tabla5_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla5_tabla5_campo1_seq OWNED BY tabla5.tabla5_campo1;


--
-- Name: tabla5_tabla5_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla5_tabla5_campo1_seq', 12, true);


--
-- Name: tabla6; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla6 (
    tabla6_campo1 integer NOT NULL,
    tabla6_campo2 character varying(50) NOT NULL,
    tabla6_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla6 OWNER TO carbonara;

--
-- Name: TABLE tabla6; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla6 IS 'Enfermedades';


--
-- Name: COLUMN tabla6.tabla6_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla6.tabla6_campo1 IS 'identificador';


--
-- Name: COLUMN tabla6.tabla6_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla6.tabla6_campo2 IS 'descripción';


--
-- Name: COLUMN tabla6.tabla6_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla6.tabla6_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla6_tabla6_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla6_tabla6_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla6_tabla6_campo1_seq OWNER TO carbonara;

--
-- Name: tabla6_tabla6_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla6_tabla6_campo1_seq OWNED BY tabla6.tabla6_campo1;


--
-- Name: tabla6_tabla6_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla6_tabla6_campo1_seq', 2, true);


--
-- Name: tabla7; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla7 (
    tabla7_campo1 integer NOT NULL,
    tabla7_campo2 character varying(50) NOT NULL,
    tabla7_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla7 OWNER TO carbonara;

--
-- Name: TABLE tabla7; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla7 IS 'Tipo de vivienda';


--
-- Name: COLUMN tabla7.tabla7_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla7.tabla7_campo1 IS 'identificador';


--
-- Name: COLUMN tabla7.tabla7_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla7.tabla7_campo2 IS 'descripción';


--
-- Name: COLUMN tabla7.tabla7_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla7.tabla7_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla7_tabla7_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla7_tabla7_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla7_tabla7_campo1_seq OWNER TO carbonara;

--
-- Name: tabla7_tabla7_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla7_tabla7_campo1_seq OWNED BY tabla7.tabla7_campo1;


--
-- Name: tabla7_tabla7_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla7_tabla7_campo1_seq', 7, true);


--
-- Name: tabla8; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla8 (
    tabla8_campo1 integer NOT NULL,
    tabla8_campo2 character varying(50) NOT NULL,
    tabla8_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla8 OWNER TO carbonara;

--
-- Name: TABLE tabla8; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla8 IS 'Material predominante del piso';


--
-- Name: COLUMN tabla8.tabla8_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla8.tabla8_campo1 IS 'identificador';


--
-- Name: COLUMN tabla8.tabla8_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla8.tabla8_campo2 IS 'descripción';


--
-- Name: COLUMN tabla8.tabla8_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla8.tabla8_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla8_tabla8_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla8_tabla8_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla8_tabla8_campo1_seq OWNER TO carbonara;

--
-- Name: tabla8_tabla8_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla8_tabla8_campo1_seq OWNED BY tabla8.tabla8_campo1;


--
-- Name: tabla8_tabla8_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla8_tabla8_campo1_seq', 6, true);


--
-- Name: tabla9; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE tabla9 (
    tabla9_campo1 integer NOT NULL,
    tabla9_campo2 character varying(50) NOT NULL,
    tabla9_campo3 boolean DEFAULT true NOT NULL,
    fregistro date DEFAULT now() NOT NULL,
    activo boolean DEFAULT true NOT NULL
);


ALTER TABLE cc1.tabla9 OWNER TO carbonara;

--
-- Name: TABLE tabla9; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE tabla9 IS 'Material predominante del techo';


--
-- Name: COLUMN tabla9.tabla9_campo1; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla9.tabla9_campo1 IS 'identificador';


--
-- Name: COLUMN tabla9.tabla9_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla9.tabla9_campo2 IS 'descripción';


--
-- Name: COLUMN tabla9.tabla9_campo3; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN tabla9.tabla9_campo3 IS 'Visible: identifica si un registro es visible';


--
-- Name: tabla9_tabla9_campo1_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE tabla9_tabla9_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.tabla9_tabla9_campo1_seq OWNER TO carbonara;

--
-- Name: tabla9_tabla9_campo1_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE tabla9_tabla9_campo1_seq OWNED BY tabla9.tabla9_campo1;


--
-- Name: tabla9_tabla9_campo1_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('tabla9_tabla9_campo1_seq', 2, true);


--
-- Name: usuarios; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE usuarios (
    id integer NOT NULL,
    tabla20_campo2 character varying(9) NOT NULL,
    usuario character varying(10) NOT NULL,
    clave character varying(250) NOT NULL,
    default_mask character varying(30) NOT NULL,
    nivel_acceso smallint DEFAULT 0 NOT NULL,
    fregistro date DEFAULT ('now'::text)::date NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    usr integer,
    ip inet
);


ALTER TABLE cc1.usuarios OWNER TO carbonara;

--
-- Name: TABLE usuarios; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON TABLE usuarios IS 'Registro de los usuarios que pueden hacer login al sistema';


--
-- Name: COLUMN usuarios.tabla20_campo2; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN usuarios.tabla20_campo2 IS 'Cédula. Debe ser habitante de la comunidad';


--
-- Name: COLUMN usuarios.default_mask; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN usuarios.default_mask IS 'máscara por defecto al iniciar sesión';


--
-- Name: COLUMN usuarios.nivel_acceso; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN usuarios.nivel_acceso IS 'inicia sin acceso';


--
-- Name: COLUMN usuarios.usr; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN usuarios.usr IS 'Usuario que realiza la transaccción';


--
-- Name: COLUMN usuarios.ip; Type: COMMENT; Schema: cc1; Owner: carbonara
--

COMMENT ON COLUMN usuarios.ip IS 'Dirección IP desde donde se realiza la transacción';


--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE usuario_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.usuario_id_seq OWNER TO carbonara;

--
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: cc1; Owner: carbonara
--

ALTER SEQUENCE usuario_id_seq OWNED BY usuarios.id;


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('usuario_id_seq', 18, true);


--
-- Name: usuario_menu; Type: TABLE; Schema: cc1; Owner: carbonara; Tablespace:
--

CREATE TABLE usuario_menu (
    id_usuario integer NOT NULL,
    id_menu integer NOT NULL
);


ALTER TABLE cc1.usuario_menu OWNER TO carbonara;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: cc1; Owner: carbonara
--

CREATE SEQUENCE usuarios_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE cc1.usuarios_id_seq OWNER TO carbonara;

--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: cc1; Owner: carbonara
--

SELECT pg_catalog.setval('usuarios_id_seq', 3, true);


SET search_path = encuestas, pg_catalog;

--
-- Name: censo; Type: TABLE; Schema: encuestas; Owner: carbonara; Tablespace:
--

CREATE TABLE censo (
    id integer NOT NULL,
    nombre character varying(50),
    fecha_nacimiento date,
    salario integer,
    profesion integer,
    apellido character varying(50),
    sexo character varying(1),
    grado_instruccion integer,
    estudia boolean,
    trabaja boolean,
    cedula character varying(9),
    ocupacion integer,
    situacion_laboral integer,
    parentesco integer,
    estado_civil integer,
    correo character varying(100),
    telefono character varying(12),
    agno_comunidad character varying(4),
    sector integer,
    vivienda integer,
    politica_habitacional boolean,
    seguro boolean,
    vehiculo boolean,
    terreno boolean,
    ubicacion_terreno text,
    padece_enfermedad boolean,
    requiere_medicamento_porvida boolean,
    cual_tratamiento text,
    requiere_material_medico boolean,
    cual_material_medico text,
    requiere_operacion boolean,
    cual_operacion text,
    requiere_servicio_salud boolean,
    cual_servicio_salud text,
    embarazada boolean,
    tabla20_campo31 boolean,
    tabla20_campo32 boolean,
    tabla20_campo33 text,
    tabla20_campo34 boolean,
    tabla20_campo35 character varying(250),
    tabla20_campo36 character varying(250),
    tabla20_campo37 boolean,
    tabla20_campo38 character varying(250),
    tabla20_campo39 boolean,
    tabla20_campo40 character varying(250),
    tabla20_campo41 boolean,
    tabla20_campo42 character varying(250),
    tabla20_campo43 boolean,
    tabla20_campo44 character varying(250),
    tabla20_campo45 character varying(250),
    tabla20_campo46 character varying(250),
    tabla20_campo47 character varying(250),
    tabla20_campo48 boolean,
    tabla20_campo49 character varying(250),
    tabla20_campo50 boolean,
    tabla20_campo51 character varying(250),
    tabla20_campo52 boolean,
    tabla20_campo53 character varying(250),
    tabla20_campo54 boolean,
    tabla20_campo55 integer,
    tabla20_campo56 boolean,
    tabla20_campo57 integer,
    tabla20_campo58 boolean,
    tabla20_campo59 boolean,
    tabla20_campo60 boolean,
    tabla20_campo61 boolean,
    tabla20_campo62 boolean,
    tabla20_campo63 integer,
    tabla20_campo64 boolean,
    tabla20_campo65 boolean,
    tabla20_campo66 integer,
    tabla20_campo67 boolean,
    tabla20_campo68 text,
    cual_enfermedad character varying(250)
);


ALTER TABLE encuestas.censo OWNER TO carbonara;

--
-- Name: COLUMN censo.agno_comunidad; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.agno_comunidad IS 'Año en que llegó a la comunidad';


--
-- Name: COLUMN censo.vivienda; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.vivienda IS 'Nombre o número de la vivienda';


--
-- Name: COLUMN censo.cual_tratamiento; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.cual_tratamiento IS 'En caso de requerir tratamiento de por vida, especificar cuál';


--
-- Name: COLUMN censo.cual_material_medico; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.cual_material_medico IS 'En caso de requerir material médico, especificar cuál';


--
-- Name: COLUMN censo.cual_operacion; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.cual_operacion IS 'En caso de requerir operación, especificar cuál';


--
-- Name: COLUMN censo.cual_servicio_salud; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.cual_servicio_salud IS 'En caso de requerir servicio de salud, especificar cuál';


--
-- Name: COLUMN censo.embarazada; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.embarazada IS 'En caso de ser Femenino, en edad fertil';


--
-- Name: COLUMN censo.tabla20_campo31; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo31 IS 'Sabe cómo actuar en caso de una emergencia';


--
-- Name: COLUMN censo.tabla20_campo32; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo32 IS 'Conoce los organismos a quien acudir en caso de una emergencia';


--
-- Name: COLUMN censo.tabla20_campo33; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo33 IS 'Cuál organismo conoce en caso de una emergencia';


--
-- Name: COLUMN censo.tabla20_campo34; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo34 IS 'Conoce los números de emergencia';


--
-- Name: COLUMN censo.tabla20_campo35; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo35 IS 'Cuál número de emergencia conoce';


--
-- Name: COLUMN censo.tabla20_campo36; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo36 IS 'a dónde se dirigría en caso de una emergencia';


--
-- Name: COLUMN censo.tabla20_campo37; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo37 IS 'Ha recibido entrenamiento para casos de emergencia';


--
-- Name: COLUMN censo.tabla20_campo38; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo38 IS 'Qué entrenamiento ha recibido para casos de emergencia';


--
-- Name: COLUMN censo.tabla20_campo39; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo39 IS 'Le gustaría tomar talleres para casos de emergencia';


--
-- Name: COLUMN censo.tabla20_campo40; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo40 IS 'Qué talleres le gustaría tomar para casos de emergencia';


--
-- Name: COLUMN censo.tabla20_campo41; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo41 IS 'Posee alguna habilidad artesanal';


--
-- Name: COLUMN censo.tabla20_campo42; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo42 IS 'Cuál habilidad artesanal posee';


--
-- Name: COLUMN censo.tabla20_campo43; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo43 IS 'Desea integrar algún grupo cultural';


--
-- Name: COLUMN censo.tabla20_campo44; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo44 IS 'Cuál grupo cultural desea integrar';


--
-- Name: COLUMN censo.tabla20_campo45; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo45 IS 'Qué clase de talleres le gusaría que el INCES dictara en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo46; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo46 IS 'Qué otros talleres le gustaría que se dictaran en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo47; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo47 IS 'Cuál considera que es la necesidad prioritaria';


--
-- Name: COLUMN censo.tabla20_campo48; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo48 IS 'Existe algún problema ambiental en su sector';


--
-- Name: COLUMN censo.tabla20_campo49; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo49 IS 'cuál problema ambiental existe en su sector';


--
-- Name: COLUMN censo.tabla20_campo50; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo50 IS 'Desea que exista un espacio para el esparcimiento en su sector';


--
-- Name: COLUMN censo.tabla20_campo51; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo51 IS 'Especifique qué clase de espacio para el esparcimiento';


--
-- Name: COLUMN censo.tabla20_campo52; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo52 IS 'Existe algún problema de seguridad en su sector';


--
-- Name: COLUMN censo.tabla20_campo53; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo53 IS 'Cuál problema de seguridad existe';


--
-- Name: COLUMN censo.tabla20_campo54; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo54 IS 'Practica algún deporte';


--
-- Name: COLUMN censo.tabla20_campo55; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo55 IS 'Cuál deporte practica';


--
-- Name: COLUMN censo.tabla20_campo56; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo56 IS 'Le gustaría practicar algún deporte';


--
-- Name: COLUMN censo.tabla20_campo57; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo57 IS 'cuál deporte le gustaría practicar';


--
-- Name: COLUMN censo.tabla20_campo58; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo58 IS 'Percibe problemas de drogas en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo59; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo59 IS 'Percibe problemas de alcoholismo en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo60; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo60 IS 'Percibe problemas de delincuencia en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo61; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo61 IS 'Percibe otro problema en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo62; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo62 IS 'Le gustaría participar en el Consejo Comunal';


--
-- Name: COLUMN censo.tabla20_campo63; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo63 IS 'En cuál comité del Consejo Comunal le gustaría participar';


--
-- Name: COLUMN censo.tabla20_campo64; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo64 IS 'Sabe sí se está implementando alguna Misión en la comunidad';


--
-- Name: COLUMN censo.tabla20_campo65; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo65 IS 'Se beneficia de alguna Misión';


--
-- Name: COLUMN censo.tabla20_campo66; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo66 IS 'De cuál Misión se beneficia';


--
-- Name: COLUMN censo.tabla20_campo67; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo67 IS 'Desea un crédito Socioproductivo';


--
-- Name: COLUMN censo.tabla20_campo68; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.tabla20_campo68 IS 'Especcifique para que le gustaría un crédito Socioproductivo';


--
-- Name: COLUMN censo.cual_enfermedad; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN censo.cual_enfermedad IS 'En caso de padecer una enfermedad, especificar cuál';


--
-- Name: censo_id_seq; Type: SEQUENCE; Schema: encuestas; Owner: carbonara
--

CREATE SEQUENCE censo_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE encuestas.censo_id_seq OWNER TO carbonara;

--
-- Name: censo_id_seq; Type: SEQUENCE OWNED BY; Schema: encuestas; Owner: carbonara
--

ALTER SEQUENCE censo_id_seq OWNED BY censo.id;


--
-- Name: censo_id_seq; Type: SEQUENCE SET; Schema: encuestas; Owner: carbonara
--

SELECT pg_catalog.setval('censo_id_seq', 574, true);


--
-- Name: habitantes; Type: TABLE; Schema: encuestas; Owner: carbonara; Tablespace:
--

CREATE TABLE habitantes (
    id integer NOT NULL,
    cedula character varying(9),
    apellido character varying(50),
    nombre character varying(50),
    edad smallint,
    sexo character varying(1),
    instruccion character varying(30),
    profesion character varying(50),
    sector character varying(50),
    fecha_nacimiento date,
    salario money,
    agno_comunidad character varying(4)
);


ALTER TABLE encuestas.habitantes OWNER TO carbonara;

--
-- Name: COLUMN habitantes.agno_comunidad; Type: COMMENT; Schema: encuestas; Owner: carbonara
--

COMMENT ON COLUMN habitantes.agno_comunidad IS 'Tiempo en años, viviendo en la comunidad';


--
-- Name: habitantes_id_seq; Type: SEQUENCE; Schema: encuestas; Owner: carbonara
--

CREATE SEQUENCE habitantes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE encuestas.habitantes_id_seq OWNER TO carbonara;

--
-- Name: habitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: encuestas; Owner: carbonara
--

ALTER SEQUENCE habitantes_id_seq OWNED BY habitantes.id;


--
-- Name: habitantes_id_seq; Type: SEQUENCE SET; Schema: encuestas; Owner: carbonara
--

SELECT pg_catalog.setval('habitantes_id_seq', 1, false);


SET search_path = public, pg_catalog;

--
-- Name: auditor; Type: TABLE; Schema: public; Owner: carbonara; Tablespace:
--

CREATE TABLE auditor (
    id integer,
    op text,
    fecha timestamp with time zone DEFAULT now(),
    tbl character varying,
    ip inet,
    usr character varying(30)
);


ALTER TABLE public.auditor OWNER TO carbonara;

--
-- Name: censo_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE censo_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.censo_id_seq OWNER TO carbonara;

--
-- Name: censo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('censo_id_seq', 1, true);


--
-- Name: encuestador_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE encuestador_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.encuestador_id_seq OWNER TO carbonara;

--
-- Name: encuestador_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('encuestador_id_seq', 99, true);


--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE menu_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.menu_id_seq OWNER TO carbonara;

--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('menu_id_seq', 30, true);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO carbonara;

--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('roles_id_seq', 1, false);


--
-- Name: tabla10_tabla10_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla10_tabla10_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla10_tabla10_campo1_seq OWNER TO carbonara;

--
-- Name: tabla10_tabla10_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla10_tabla10_campo1_seq', 4, true);


--
-- Name: tabla11_tabla11_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla11_tabla11_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla11_tabla11_campo1_seq OWNER TO carbonara;

--
-- Name: tabla11_tabla11_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla11_tabla11_campo1_seq', 2, true);


--
-- Name: tabla12_tabla12_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla12_tabla12_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla12_tabla12_campo1_seq OWNER TO carbonara;

--
-- Name: tabla12_tabla12_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla12_tabla12_campo1_seq', 4, true);


--
-- Name: tabla13_tabla13_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla13_tabla13_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla13_tabla13_campo1_seq OWNER TO carbonara;

--
-- Name: tabla13_tabla13_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla13_tabla13_campo1_seq', 4, true);


--
-- Name: tabla14_tabla14_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla14_tabla14_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla14_tabla14_campo1_seq OWNER TO carbonara;

--
-- Name: tabla14_tabla14_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla14_tabla14_campo1_seq', 7, true);


--
-- Name: tabla18_tabla18_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla18_tabla18_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla18_tabla18_campo1_seq OWNER TO carbonara;

--
-- Name: tabla18_tabla18_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla18_tabla18_campo1_seq', 2, true);


--
-- Name: tabla21_tabla21_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla21_tabla21_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla21_tabla21_campo1_seq OWNER TO carbonara;

--
-- Name: tabla21_tabla21_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla21_tabla21_campo1_seq', 3, true);


--
-- Name: tabla22_tabla22_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla22_tabla22_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla22_tabla22_campo1_seq OWNER TO carbonara;

--
-- Name: tabla22_tabla22_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla22_tabla22_campo1_seq', 3, true);


--
-- Name: tabla2_tabla2_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla2_tabla2_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla2_tabla2_campo1_seq OWNER TO carbonara;

--
-- Name: tabla2_tabla2_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla2_tabla2_campo1_seq', 24, true);


--
-- Name: tabla4_tabla4_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla4_tabla4_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla4_tabla4_campo1_seq OWNER TO carbonara;

--
-- Name: tabla4_tabla4_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla4_tabla4_campo1_seq', 1, true);


--
-- Name: tabla5_tabla5_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla5_tabla5_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla5_tabla5_campo1_seq OWNER TO carbonara;

--
-- Name: tabla5_tabla5_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla5_tabla5_campo1_seq', 7, true);


--
-- Name: tabla6_tabla6_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla6_tabla6_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla6_tabla6_campo1_seq OWNER TO carbonara;

--
-- Name: tabla6_tabla6_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla6_tabla6_campo1_seq', 1, true);


--
-- Name: tabla7_tabla7_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla7_tabla7_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla7_tabla7_campo1_seq OWNER TO carbonara;

--
-- Name: tabla7_tabla7_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla7_tabla7_campo1_seq', 14, true);


--
-- Name: tabla8_tabla8_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla8_tabla8_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla8_tabla8_campo1_seq OWNER TO carbonara;

--
-- Name: tabla8_tabla8_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla8_tabla8_campo1_seq', 10, true);


--
-- Name: tabla9_tabla9_campo1_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE tabla9_tabla9_campo1_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tabla9_tabla9_campo1_seq OWNER TO carbonara;

--
-- Name: tabla9_tabla9_campo1_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('tabla9_tabla9_campo1_seq', 8, true);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE users_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO carbonara;

--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('users_id_seq', 3, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: carbonara
--

CREATE SEQUENCE usuarios_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_seq OWNER TO carbonara;

--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: carbonara
--

SELECT pg_catalog.setval('usuarios_id_seq', 7, true);


SET search_path = cc1, pg_catalog;

--
-- Name: id; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE menu ALTER COLUMN id SET DEFAULT nextval('menu_id_seq'::regclass);


--
-- Name: tabla1_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla0 ALTER COLUMN tabla1_campo1 SET DEFAULT nextval('tabla1_tabla1_campo1_seq'::regclass);


--
-- Name: tabla1_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla1 ALTER COLUMN tabla1_campo1 SET DEFAULT nextval('tabla1_tabla1_campo1_seq1'::regclass);


--
-- Name: tabla10_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla10 ALTER COLUMN tabla10_campo1 SET DEFAULT nextval('tabla10_tabla10_campo1_seq'::regclass);


--
-- Name: tabla11_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla11 ALTER COLUMN tabla11_campo1 SET DEFAULT nextval('tabla11_tabla11_campo1_seq'::regclass);


--
-- Name: tabla12_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla12 ALTER COLUMN tabla12_campo1 SET DEFAULT nextval('tabla12_tabla12_campo1_seq'::regclass);


--
-- Name: tabla13_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla13 ALTER COLUMN tabla13_campo1 SET DEFAULT nextval('tabla13_tabla13_campo1_seq'::regclass);


--
-- Name: tabla14_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla14 ALTER COLUMN tabla14_campo1 SET DEFAULT nextval('tabla14_tabla14_campo1_seq'::regclass);


--
-- Name: tabla15_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla15 ALTER COLUMN tabla15_campo1 SET DEFAULT nextval('tabla15_tabla15_campo1_seq'::regclass);


--
-- Name: tabla16_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla16 ALTER COLUMN tabla16_campo1 SET DEFAULT nextval('tabla16_tabla16_campo1_seq'::regclass);


--
-- Name: tabla17_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla17 ALTER COLUMN tabla17_campo1 SET DEFAULT nextval('tabla17_tabla17_campo1_seq'::regclass);


--
-- Name: tabla18_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla18 ALTER COLUMN tabla18_campo1 SET DEFAULT nextval('tabla18_campo1_seq1'::regclass);


--
-- Name: tabla19_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla19 ALTER COLUMN tabla19_campo1 SET DEFAULT nextval('tabla19_campo1_seq'::regclass);


--
-- Name: tabla2_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla2 ALTER COLUMN tabla2_campo1 SET DEFAULT nextval('tabla2_tabla2_campo1_seq'::regclass);


--
-- Name: tabla20_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla20 ALTER COLUMN tabla20_campo1 SET DEFAULT nextval('tabla20_tabla20_campo1_seq'::regclass);


--
-- Name: tabla21_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla21 ALTER COLUMN tabla21_campo1 SET DEFAULT nextval('tabla21_campo1_seq1'::regclass);


--
-- Name: tabla22_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla22 ALTER COLUMN tabla22_campo1 SET DEFAULT nextval('tabla22_campo1_seq1'::regclass);


--
-- Name: tabla3_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla3 ALTER COLUMN tabla3_campo1 SET DEFAULT nextval('tabla3_tabla3_campo1_seq'::regclass);


--
-- Name: tabla4_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla4 ALTER COLUMN tabla4_campo1 SET DEFAULT nextval('tabla4_tabla4_campo1_seq'::regclass);


--
-- Name: tabla5_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla5 ALTER COLUMN tabla5_campo1 SET DEFAULT nextval('tabla5_tabla5_campo1_seq'::regclass);


--
-- Name: tabla6_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla6 ALTER COLUMN tabla6_campo1 SET DEFAULT nextval('tabla6_tabla6_campo1_seq'::regclass);


--
-- Name: tabla7_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla7 ALTER COLUMN tabla7_campo1 SET DEFAULT nextval('tabla7_tabla7_campo1_seq'::regclass);


--
-- Name: tabla8_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla8 ALTER COLUMN tabla8_campo1 SET DEFAULT nextval('tabla8_tabla8_campo1_seq'::regclass);


--
-- Name: tabla9_campo1; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE tabla9 ALTER COLUMN tabla9_campo1 SET DEFAULT nextval('tabla9_tabla9_campo1_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: cc1; Owner: carbonara
--

ALTER TABLE usuarios ALTER COLUMN id SET DEFAULT nextval('usuario_id_seq'::regclass);


SET search_path = encuestas, pg_catalog;

--
-- Name: id; Type: DEFAULT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE censo ALTER COLUMN id SET DEFAULT nextval('censo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE habitantes ALTER COLUMN id SET DEFAULT nextval('habitantes_id_seq'::regclass);


SET search_path = auditoria, pg_catalog;

--
-- Data for Name: historico; Type: TABLE DATA; Schema: auditoria; Owner: carbonara
--



SET search_path = cc1, pg_catalog;

--
-- Data for Name: crear_usuarios; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: menu; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO menu VALUES (20, 'crearmenu', 'Menú', 3, 'admin', 'admin', 21, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (12, 'crearmenu', 'Menú', 3, 'admin', 'admin', 3, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (21, 'crearusuario', 'Usuarios', 3, 'admin', 'admin', 22, 2, true, 10, 'menuClick', '', 'Administración->Usuarios', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (14, 'crearusuario', 'Usuarios', 3, 'admin', 'admin', 4, 2, true, 10, 'menuClick', '', 'Administración->Usuarios', '2010-08-15', false, 1, '::1');
INSERT INTO menu VALUES (22, 'usuariosmenu', 'Permisos', 3, 'admin', 'admin', 23, 2, true, 10, 'menuclick', '', 'Administración->Permisos', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (17, 'usuariosmenu', 'Permisos', 3, 'admin', 'admin', 5, 2, true, 10, 'menuclick', '', 'Administración->Permisos', '2010-08-15', false, 1, '::1');
INSERT INTO menu VALUES (1, 'main', 'Inicio', NULL, NULL, NULL, 1, NULL, true, 1, 'menuClick', NULL, 'Inicio', '2010-08-11', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (2, 'salir', 'Salir', NULL, NULL, NULL, 10, NULL, true, 1, 'restart', NULL, 'Salir', '2010-08-11', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (3, 'admin', 'Administración', NULL, NULL, NULL, 2, NULL, true, 10, '', NULL, 'Administración', '2010-08-11', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (4, 'crearMenu', 'Menú', 3, 'admin', 'Administración', 3, 2, true, 1, 'menuClick', NULL, 'Menú', '2010-08-11', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (5, 'crearmenu', 'Menú', 3, 'admin', 'Admin', 4, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (6, 'crearmenu', 'Menú', 3, 'admin', 'Admin', 3, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (7, 'crearmenu', 'Menú', 3, 'admin', 'admin', 6, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (8, 'crearmenu', 'Menú', 3, 'admin', 'admin', 6, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (9, 'crearmenu', 'Menú', 3, 'admin', 'admin', 6, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (10, 'crearmenu', 'Menú', 3, 'admin', 'admin', 3, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (11, 'crearmenu', 'Menú', 3, 'admin', 'admin', 4, 2, true, 1, 'menuClick', '', 'Administración->Menú', '2010-08-12', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (36, 'tabla5', 'Disciplinas Deportivas', 19, 'basicas_familia', 'basicas_familia', 35, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Disciplinas Deportivas', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (38, 'basicas_vivienda', 'Básicas De Vivienda', NULL, NULL, NULL, 4, NULL, true, 1, '', '', 'Básicas de Vivienda', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (26, 'tabla2', 'Ocupación', 19, 'basicas_familia', 'Básicas de Familia', 32, 3, true, 1, '', '', 'Básicas de Familia->Ocupación', '2010-08-21', false, 1, '::1');
INSERT INTO menu VALUES (30, 'tabla2', 'Ocupación', 19, 'basicas_familia', 'Básicas de Familia', 32, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Ocupación', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (29, 'tabla2', 'Ocupación', 19, 'basicas_familia', 'Básicas de Familia', 32, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Ocupación', '2010-08-21', false, 1, '::1');
INSERT INTO menu VALUES (31, 'tabla3', 'Profesión', 19, 'basicas_familia', 'Básicas de Familia', 33, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Profesión', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (33, 'tabla4', 'Parentescos', 19, 'basicas_familia', 'Básicas de Familia', 34, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Parentescos', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (24, 'tabla1', 'Grado De Instrucción', 19, 'basicas_familia', 'Básicas de Familia', 31, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Grado de Instrucción', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (19, 'basicas_familia', 'Básicas de Familia', NULL, NULL, NULL, 3, NULL, true, 1, '', '', 'Básicas de Familia', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (42, 'tabla7', 'Tipo De Vivienda', 38, 'basicas_vivienda', 'basicas_vivienda', 41, 4, true, 1, 'menuClick', '', 'Básicas De Vivienda->Tipo de Vivienda', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (43, 'tabla6', 'Enfermedades', 19, 'basicas_familia', 'basicas_familia', 36, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Enfermedades', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (40, 'tabla6', 'Enfermedades', 19, 'bascias_familia', 'basicas_familia', 36, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Enfermedades', '2010-08-21', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (45, 'tabla8', 'Material Utilizado En El Piso', 38, 'basicas_vivienda', 'basicas_vivienda', 42, 4, true, 1, 'menuClick', '', 'Básicas De Vivienda->Material Utilizado en el Piso', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (47, 'tabla9', 'Material Utilizado En El Techo', 38, 'basicas_vivienda', 'basicas_vivienda', 43, 4, true, 1, 'menuClick', '', 'Básicas De Vivienda->Material Utilizado en el Techo', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (49, 'tabla10', 'Tenencia', 38, 'basicas_vivienda', 'basicas_vivienda', 44, 4, true, 1, 'menuClick', '', 'Básicas De Vivienda->Tenencia', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (53, 'tabla11', 'Material Utilizado En Las Paredes Exteriores', 38, 'basicas_vivienda', 'basicas_vivienda', 45, 4, true, 1, 'menuClick', '', 'Básicas De Vivienda->Material Utilizado en las Paredes Exteriores', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (55, 'basicas_cc', 'Básicas Del Consejo Comunal', NULL, NULL, NULL, 5, NULL, true, 1, '', '', 'Básicas del Consejo Comunal', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (57, 'tabla12', 'Comités', 55, 'basicas_cc', 'basicas_cc', 51, 5, true, 1, 'menuClick', '', 'Básicas Del Consejo Comunal->Comités', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (59, 'censos', 'Censos', NULL, NULL, NULL, 6, NULL, true, 1, '', '', 'Censos', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (63, 'tabla14', 'Viviendas', 59, 'censos', 'censos', 62, 6, true, 1, 'menuClick', '', 'Censos->Viviendas', '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (67, 'reportes', 'Reportes', NULL, NULL, NULL, 8, NULL, true, 1, '', '', 'Reportes', '2010-08-23', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (69, 'poblacionelectoral', 'Población Electoral', 67, 'reportes', 'reportes', 81, 8, true, 1, 'menuClick', '', 'Reportes->Población Electoral', '2010-08-23', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (70, 'constancia_residencia', 'Constancia De Residencia', 67, 'reportes', 'reportes', 82, 8, true, 1, 'menuClick', '', 'Reportes->Constancia de Residencia', '2010-08-25', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (28, 'tabla3', 'Profesión', 19, 'basicas_familia', 'Básicas de Familia', 33, 3, true, 1, 'menuClick', '', 'Básicas de Familia->Profesión', '2010-08-21', false, 1, '::1');
INSERT INTO menu VALUES (76, 'tabla20', 'Censo De Habitantes', 59, 'censos', 'censos', 63, 6, true, 1, 'menuClick', '', 'Censos->Censo de Habitantes', '2010-10-12', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (72, 'tabla13', 'Sectores', 55, 'basicas_cc', 'basicas_cc', 58, 5, true, 1, 'menuClick', '', 'Básicas Del Consejo Comunal->Sectores', '2010-09-11', true, 1, '::1');
INSERT INTO menu VALUES (61, 'tabla13', 'Sectores', 59, 'censos', 'censos', 61, 6, true, 1, 'menuClick', '', 'Censos->Sectores', '2010-08-21', false, 1, '127.0.0.1');
INSERT INTO menu VALUES (71, 'tabla13', 'Sectores', 55, 'censos', 'basicas_cc', 61, 5, true, 1, 'menuClick', '', 'Básicas Del Consejo Comunal->Sectores', '2010-09-11', false, 1, '::1');
INSERT INTO menu VALUES (92, 'constancias', 'Constancias', NULL, NULL, NULL, 9, NULL, true, 1, NULL, NULL, 'Constancias', '2010-10-18', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (94, 'graficos', 'Gráficos', NULL, NULL, NULL, 7, NULL, true, 1, NULL, NULL, 'Gráficos', '2010-10-18', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (93, 'constancia_residencia', 'Residencia', 92, 'constancias', 'Constancias', 91, 9, true, 1, 'menuClick', NULL, 'Constancias->Residencia', '2010-10-18', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (95, 'indicadores_habitantes', 'Indicadores', 94, 'graficos', NULL, 71, 7, true, 1, 'menuClick', NULL, 'Graficos->Indicadores', '2010-10-18', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (96, 'habitantes', 'Habitantes', 59, 'censos', NULL, 64, 6, true, 1, 'menuClick', NULL, 'Censos->Habitantes', '2010-10-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (65, 'habitantes_prueba', 'Habitantes Censo De Prueba', 59, 'censos', 'censos', 69, 6, true, 1, 'menuClick', NULL, 'Censos->Habitantes Censo De Prueba', '2010-10-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (97, 'tabla21', 'Salarios', 19, 'basicas_familia', NULL, 37, 3, true, 1, 'menuClick', NULL, 'Básicas de Familia->Salarios', '2010-10-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (98, 'tabla18', 'Situación Laboral', 19, 'basicas_familia', NULL, 38, 3, true, 1, 'menuClick', NULL, 'Básicas de Familia->Situación Laboral', '2010-10-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (99, 'tabla22', 'Estado Civil', 19, 'basicas_familia', NULL, 39, 3, true, 1, 'menuClick', NULL, 'Básicas de Familia->Estado Civil', '2010-10-21', true, 1, '127.0.0.1');
INSERT INTO menu VALUES (100, 'constancia_bajos_recursos', 'Bajos Recursos', 92, 'constancias', NULL, 92, 9, true, 1, 'menuClick', NULL, 'Constancias->Bajos Recursos', '2010-10-22', true, 1, '::1');
INSERT INTO menu VALUES (101, 'tabla17', 'Voceros', 55, 'basicas_cc', NULL, 52, 5, true, 1, 'menuClick', NULL, 'Básicas Del Consejo Comunal->Voceros', '2010-10-22', true, 1, '127.0.0.1');


--
-- Data for Name: tabla0; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: tabla1; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla1 VALUES (2, 'Abogado', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (3, 'Bachiller', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (4, 'Basica', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (5, 'Bombero', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (6, 'Guardia Nacional', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (7, 'Inicial', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (8, 'Licenciada', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (9, 'Post Grado', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (10, 'Primaria', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (11, 'Sin Ins.', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (12, 'Tecnico Med.', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (13, 'Tecnico Sup.', '2010-10-22', true, true);
INSERT INTO tabla1 VALUES (14, 'Universitario', '2010-10-22', true, true);


--
-- Data for Name: tabla10; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla10 VALUES (2, 'Alquiler', true, '2010-08-25', true);
INSERT INTO tabla10 VALUES (3, 'Propia', true, '2010-08-25', true);
INSERT INTO tabla10 VALUES (4, 'Herencia', true, '2010-08-25', true);


--
-- Data for Name: tabla11; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla11 VALUES (2, 'Bloque', true, '2010-08-25', true);


--
-- Data for Name: tabla12; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla12 VALUES (37, 'Vivienda', '2010-08-25', true);
INSERT INTO tabla12 VALUES (1, 'Unidad Ejecutiva', '2010-09-11', true);
INSERT INTO tabla12 VALUES (3, 'Unidad De Contraloría Social', '2010-09-11', true);
INSERT INTO tabla12 VALUES (2, 'Unidad Administrativa Y Financiera', '2010-09-11', true);


--
-- Data for Name: tabla13; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla13 VALUES (2, 'Calle Dr Ramon Parra Picon', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (3, 'Calle La Paz', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (4, 'Calle Rosa Mistica', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (5, 'Davila', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (6, 'Giraluna', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (7, 'Los Davila', '2010-10-22', true, true);
INSERT INTO tabla13 VALUES (8, 'Pedregal', '2010-10-22', true, true);


--
-- Data for Name: tabla14; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla14 VALUES (5, 6, 'Casa De Laura', 8.6040892106067872, -71.190955638885498, '2010-07-22', true, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tabla14 VALUES (12, 4, 'Emiro Marquina', 8.6010731416525097, -71.190609633922577, '2010-08-25', true, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tabla14 VALUES (13, 2, 'Pablo Andrade', 8.5930320597967853, -71.193299889564514, '2010-08-25', true, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tabla14 VALUES (14, 2, 'Julia', 8.5931361987379304, -71.192564964294405, '2010-10-21', true, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tabla14 VALUES (11, 4, 'Pepitas', 8.6069678821908902, -71.190547943115206, '2010-08-25', true, 2, 0, 0, 0, 0, 0, true, true, 1, false, '', 0, '', '5');
INSERT INTO tabla14 VALUES (15, 4, 'Pepita', 8.6069678821908902, -71.190547943115206, '2010-10-22', true, 3, 0, 0, 0, 0, 3, true, true, 2, false, '', 0, '', '7');


--
-- Data for Name: tabla15; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: tabla16; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: tabla17; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla17 VALUES (23, '11952572', 2, '2010-10-22', true);
INSERT INTO tabla17 VALUES (24, '3993840', 3, '2010-10-22', true);


--
-- Data for Name: tabla18; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla18 VALUES (2, 'Organismo Público', '2010-10-21', true, true);
INSERT INTO tabla18 VALUES (1, 'Desempleado', '2010-10-21', true, true);
INSERT INTO tabla18 VALUES (3, 'Empresa Privada', '2010-10-23', true, true);
INSERT INTO tabla18 VALUES (4, 'Negocio Propio', '2010-10-23', true, true);
INSERT INTO tabla18 VALUES (5, 'Jubilado/Pensionado', '2010-10-23', true, true);
INSERT INTO tabla18 VALUES (6, 'Discapacitado', '2010-10-23', true, true);


--
-- Data for Name: tabla19; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: tabla2; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla2 VALUES (3, 'Carpintero', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (2, 'Carpintero', '2010-07-17', false, true);
INSERT INTO tabla2 VALUES (6, 'Contador', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (7, 'Latonero', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (10, 'Medico', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (11, 'Asistente Dental', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (12, 'Guachiman', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (17, 'Geógrafo', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (13, 'Geografo', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (4, 'Abogado', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (20, 'Geólogo', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (5, 'Docente', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (23, 'Docentes', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (25, 'Enfermera', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (14, 'Geólogo', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (16, 'Costurera', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (27, 'Ingeniero Civil', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (18, 'Costurera', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (28, 'Estudiante', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (26, 'Administrador', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (19, 'Estudiante De Comunicacio', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (33, 'Estudiante Comunicacion', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (30, 'Estudiante De Comunicacio', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (34, 'Costurera', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (35, 'Mecánico', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (15, 'Costurera', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (36, 'Odontologo', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (24, 'Docente', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (29, 'Administrador', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (42, 'Ama De Casa', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (32, 'Ama De Casa', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (43, 'Chaman', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (8, 'Chaman', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (38, 'Albañil', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (47, 'Albañil', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (45, 'Albañil', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (39, 'Administrador', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (52, 'Asistente De Preescolar', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (9, 'Asistente De Preescolar', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (54, 'Comunicacion Social', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (22, 'Comunicacion Social', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (55, 'Niñera', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (21, 'Albañil', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (56, 'Asistente', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (49, 'Asistente', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (57, 'Vigilante', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (31, 'Vigilante', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (58, 'Chaman', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (40, 'Chaman', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (59, 'Leo', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (61, 'Docente', '2010-08-25', true, true);
INSERT INTO tabla2 VALUES (37, 'Docente', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (63, 'Administrador', '2010-09-06', true, true);
INSERT INTO tabla2 VALUES (48, 'Administrador', '2010-08-25', false, true);
INSERT INTO tabla2 VALUES (64, 'Administradora', '2010-09-06', true, false);
INSERT INTO tabla2 VALUES (62, 'Administradora', '2010-09-06', false, true);


--
-- Data for Name: tabla20; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla20 VALUES (1, '000000000', 'Administrador', 'Administrador', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, '2010-07-31', true, '0', false, false, NULL, NULL, NULL, NULL, NULL, NULL, false, NULL, false, NULL, false, NULL, NULL, NULL, false, NULL, false, false, false, NULL, false, NULL, NULL, false, NULL, false, NULL, false, NULL, false, NULL, NULL, NULL, NULL, false, NULL, false, NULL, false, NULL, false, NULL, false, NULL, false, false, false, NULL, false, NULL, false, false, NULL, false, NULL);
INSERT INTO tabla20 VALUES (2, '111111111', 'Usuario', 'de Pruebas', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, '2010-07-31', true, '0', false, false, NULL, NULL, NULL, NULL, NULL, NULL, false, NULL, false, NULL, false, NULL, NULL, NULL, false, NULL, false, false, false, NULL, false, NULL, NULL, false, NULL, false, NULL, false, NULL, false, NULL, NULL, NULL, NULL, false, NULL, false, NULL, false, NULL, false, NULL, false, NULL, false, false, false, NULL, false, NULL, false, false, NULL, false, NULL);


--
-- Data for Name: tabla21; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla21 VALUES (1, 'No Percibe', '2010-10-21', true, true);
INSERT INTO tabla21 VALUES (2, '0 A 1000', '2010-10-21', true, true);
INSERT INTO tabla21 VALUES (3, '1001 A 2000', '2010-10-21', true, true);


--
-- Data for Name: tabla22; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla22 VALUES (1, 'Soltero', '2010-10-21', true, true);
INSERT INTO tabla22 VALUES (2, 'Casado', '2010-10-21', true, true);
INSERT INTO tabla22 VALUES (3, 'Divorciado', '2010-10-21', true, true);
INSERT INTO tabla22 VALUES (4, 'Concubino', '2010-10-23', true, true);
INSERT INTO tabla22 VALUES (5, 'Viudo', '2010-10-23', true, true);


--
-- Data for Name: tabla3; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla3 VALUES (2, 'Licenciado En Educación', '2010-07-17', true, true);
INSERT INTO tabla3 VALUES (4, 'Sinverguenza', '2010-09-11', true, false);


--
-- Data for Name: tabla4; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla4 VALUES (2, 'Jefe Del Hogar', '2010-07-17', true, true);
INSERT INTO tabla4 VALUES (3, 'Hijo(A)', '2010-10-21', true, true);
INSERT INTO tabla4 VALUES (5, 'Nieto(A)', '2010-10-21', true, true);
INSERT INTO tabla4 VALUES (7, 'Hermano', '2010-10-23', true, true);
INSERT INTO tabla4 VALUES (10, 'Esposa', '2010-10-23', true, true);
INSERT INTO tabla4 VALUES (12, 'Padre/Madre', '2010-10-23', true, true);


--
-- Data for Name: tabla5; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla5 VALUES (2, 'Baloncesto', '2010-07-17', true, true);
INSERT INTO tabla5 VALUES (1, 'Futbol', '2010-07-17', false, true);
INSERT INTO tabla5 VALUES (3, 'Voleibol', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (5, 'Natacion', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (6, 'Futbol', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (8, 'Kikinbol', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (9, 'Ciclismo De Montaña', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (7, 'Sofbol', '2010-08-25', false, true);
INSERT INTO tabla5 VALUES (11, 'Beisbol Femenino', '2010-08-25', true, true);
INSERT INTO tabla5 VALUES (4, 'Beisbol', '2010-08-25', false, true);
INSERT INTO tabla5 VALUES (12, 'Softbol Femenino', '2010-10-20', true, true);
INSERT INTO tabla5 VALUES (10, 'Sofbol Femenino', '2010-08-25', false, true);


--
-- Data for Name: tabla6; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: tabla7; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla7 VALUES (2, 'Casa', true, '2010-08-25', true);
INSERT INTO tabla7 VALUES (3, 'Quinta', true, '2010-08-25', true);
INSERT INTO tabla7 VALUES (6, 'Rancho', true, '2010-08-25', true);
INSERT INTO tabla7 VALUES (5, 'Apartamento', true, '2010-08-25', false);
INSERT INTO tabla7 VALUES (7, 'Casa', true, '2010-08-25', true);
INSERT INTO tabla7 VALUES (4, 'Apartamento', true, '2010-08-25', false);


--
-- Data for Name: tabla8; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO tabla8 VALUES (4, 'Ceramica', true, '2010-08-25', true);
INSERT INTO tabla8 VALUES (5, 'Granito', true, '2010-08-25', true);
INSERT INTO tabla8 VALUES (6, 'Parquet', true, '2010-08-25', true);
INSERT INTO tabla8 VALUES (3, 'Cemento', true, '2010-08-25', false);


--
-- Data for Name: tabla9; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--



--
-- Data for Name: usuario_menu; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO usuario_menu VALUES (9, 19);
INSERT INTO usuario_menu VALUES (9, 43);
INSERT INTO usuario_menu VALUES (9, 33);
INSERT INTO usuario_menu VALUES (9, 38);
INSERT INTO usuario_menu VALUES (9, 45);
INSERT INTO usuario_menu VALUES (9, 59);
INSERT INTO usuario_menu VALUES (9, 61);
INSERT INTO usuario_menu VALUES (9, 63);
INSERT INTO usuario_menu VALUES (9, 1);
INSERT INTO usuario_menu VALUES (9, 67);
INSERT INTO usuario_menu VALUES (9, 70);
INSERT INTO usuario_menu VALUES (9, 69);
INSERT INTO usuario_menu VALUES (9, 2);
INSERT INTO usuario_menu VALUES (11, 19);
INSERT INTO usuario_menu VALUES (11, 24);
INSERT INTO usuario_menu VALUES (11, 31);
INSERT INTO usuario_menu VALUES (11, 55);
INSERT INTO usuario_menu VALUES (11, 38);
INSERT INTO usuario_menu VALUES (11, 47);
INSERT INTO usuario_menu VALUES (11, 49);
INSERT INTO usuario_menu VALUES (11, 59);
INSERT INTO usuario_menu VALUES (11, 61);
INSERT INTO usuario_menu VALUES (11, 63);
INSERT INTO usuario_menu VALUES (11, 1);
INSERT INTO usuario_menu VALUES (2, 1);
INSERT INTO usuario_menu VALUES (2, 2);
INSERT INTO usuario_menu VALUES (11, 67);
INSERT INTO usuario_menu VALUES (11, 70);
INSERT INTO usuario_menu VALUES (11, 69);
INSERT INTO usuario_menu VALUES (11, 2);
INSERT INTO usuario_menu VALUES (12, 19);
INSERT INTO usuario_menu VALUES (12, 36);
INSERT INTO usuario_menu VALUES (12, 43);
INSERT INTO usuario_menu VALUES (12, 24);
INSERT INTO usuario_menu VALUES (12, 59);
INSERT INTO usuario_menu VALUES (12, 61);
INSERT INTO usuario_menu VALUES (12, 63);
INSERT INTO usuario_menu VALUES (12, 1);
INSERT INTO usuario_menu VALUES (12, 67);
INSERT INTO usuario_menu VALUES (12, 70);
INSERT INTO usuario_menu VALUES (12, 69);
INSERT INTO usuario_menu VALUES (12, 2);
INSERT INTO usuario_menu VALUES (13, 19);
INSERT INTO usuario_menu VALUES (13, 36);
INSERT INTO usuario_menu VALUES (14, 19);
INSERT INTO usuario_menu VALUES (14, 36);
INSERT INTO usuario_menu VALUES (14, 24);
INSERT INTO usuario_menu VALUES (14, 33);
INSERT INTO usuario_menu VALUES (14, 1);
INSERT INTO usuario_menu VALUES (14, 67);
INSERT INTO usuario_menu VALUES (14, 70);
INSERT INTO usuario_menu VALUES (14, 69);
INSERT INTO usuario_menu VALUES (14, 2);
INSERT INTO usuario_menu VALUES (10, 1);
INSERT INTO usuario_menu VALUES (10, 2);
INSERT INTO usuario_menu VALUES (13, 43);
INSERT INTO usuario_menu VALUES (13, 55);
INSERT INTO usuario_menu VALUES (13, 57);
INSERT INTO usuario_menu VALUES (13, 38);
INSERT INTO usuario_menu VALUES (13, 45);
INSERT INTO usuario_menu VALUES (13, 47);
INSERT INTO usuario_menu VALUES (13, 59);
INSERT INTO usuario_menu VALUES (13, 61);
INSERT INTO usuario_menu VALUES (13, 63);
INSERT INTO usuario_menu VALUES (13, 1);
INSERT INTO usuario_menu VALUES (13, 67);
INSERT INTO usuario_menu VALUES (13, 70);
INSERT INTO usuario_menu VALUES (13, 69);
INSERT INTO usuario_menu VALUES (13, 2);
INSERT INTO usuario_menu VALUES (15, 19);
INSERT INTO usuario_menu VALUES (15, 36);
INSERT INTO usuario_menu VALUES (15, 33);
INSERT INTO usuario_menu VALUES (15, 31);
INSERT INTO usuario_menu VALUES (15, 38);
INSERT INTO usuario_menu VALUES (15, 45);
INSERT INTO usuario_menu VALUES (15, 53);
INSERT INTO usuario_menu VALUES (15, 59);
INSERT INTO usuario_menu VALUES (15, 61);
INSERT INTO usuario_menu VALUES (15, 63);
INSERT INTO usuario_menu VALUES (15, 1);
INSERT INTO usuario_menu VALUES (15, 67);
INSERT INTO usuario_menu VALUES (15, 70);
INSERT INTO usuario_menu VALUES (15, 69);
INSERT INTO usuario_menu VALUES (15, 2);
INSERT INTO usuario_menu VALUES (16, 19);
INSERT INTO usuario_menu VALUES (16, 33);
INSERT INTO usuario_menu VALUES (16, 55);
INSERT INTO usuario_menu VALUES (16, 57);
INSERT INTO usuario_menu VALUES (16, 59);
INSERT INTO usuario_menu VALUES (16, 61);
INSERT INTO usuario_menu VALUES (16, 63);
INSERT INTO usuario_menu VALUES (16, 1);
INSERT INTO usuario_menu VALUES (16, 67);
INSERT INTO usuario_menu VALUES (16, 70);
INSERT INTO usuario_menu VALUES (16, 69);
INSERT INTO usuario_menu VALUES (16, 2);
INSERT INTO usuario_menu VALUES (8, 19);
INSERT INTO usuario_menu VALUES (8, 24);
INSERT INTO usuario_menu VALUES (8, 30);
INSERT INTO usuario_menu VALUES (8, 33);
INSERT INTO usuario_menu VALUES (8, 31);
INSERT INTO usuario_menu VALUES (8, 38);
INSERT INTO usuario_menu VALUES (8, 45);
INSERT INTO usuario_menu VALUES (8, 47);
INSERT INTO usuario_menu VALUES (8, 53);
INSERT INTO usuario_menu VALUES (8, 49);
INSERT INTO usuario_menu VALUES (8, 42);
INSERT INTO usuario_menu VALUES (8, 1);
INSERT INTO usuario_menu VALUES (8, 2);
INSERT INTO usuario_menu VALUES (18, 94);
INSERT INTO usuario_menu VALUES (18, 95);
INSERT INTO usuario_menu VALUES (18, 1);
INSERT INTO usuario_menu VALUES (18, 67);
INSERT INTO usuario_menu VALUES (18, 70);
INSERT INTO usuario_menu VALUES (18, 69);
INSERT INTO usuario_menu VALUES (18, 2);
INSERT INTO usuario_menu VALUES (17, 59);
INSERT INTO usuario_menu VALUES (17, 96);
INSERT INTO usuario_menu VALUES (17, 63);
INSERT INTO usuario_menu VALUES (1, 3);
INSERT INTO usuario_menu VALUES (1, 20);
INSERT INTO usuario_menu VALUES (1, 22);
INSERT INTO usuario_menu VALUES (1, 21);
INSERT INTO usuario_menu VALUES (1, 19);
INSERT INTO usuario_menu VALUES (1, 36);
INSERT INTO usuario_menu VALUES (1, 43);
INSERT INTO usuario_menu VALUES (1, 99);
INSERT INTO usuario_menu VALUES (1, 24);
INSERT INTO usuario_menu VALUES (1, 30);
INSERT INTO usuario_menu VALUES (1, 33);
INSERT INTO usuario_menu VALUES (1, 31);
INSERT INTO usuario_menu VALUES (1, 97);
INSERT INTO usuario_menu VALUES (1, 98);
INSERT INTO usuario_menu VALUES (1, 55);
INSERT INTO usuario_menu VALUES (1, 57);
INSERT INTO usuario_menu VALUES (1, 72);
INSERT INTO usuario_menu VALUES (1, 101);
INSERT INTO usuario_menu VALUES (1, 38);
INSERT INTO usuario_menu VALUES (1, 45);
INSERT INTO usuario_menu VALUES (1, 47);
INSERT INTO usuario_menu VALUES (1, 53);
INSERT INTO usuario_menu VALUES (1, 49);
INSERT INTO usuario_menu VALUES (1, 42);
INSERT INTO usuario_menu VALUES (1, 59);
INSERT INTO usuario_menu VALUES (1, 96);
INSERT INTO usuario_menu VALUES (1, 63);
INSERT INTO usuario_menu VALUES (1, 92);
INSERT INTO usuario_menu VALUES (1, 100);
INSERT INTO usuario_menu VALUES (1, 93);
INSERT INTO usuario_menu VALUES (1, 94);
INSERT INTO usuario_menu VALUES (1, 95);
INSERT INTO usuario_menu VALUES (1, 1);
INSERT INTO usuario_menu VALUES (1, 67);
INSERT INTO usuario_menu VALUES (1, 69);
INSERT INTO usuario_menu VALUES (1, 2);
INSERT INTO usuario_menu VALUES (17, 1);
INSERT INTO usuario_menu VALUES (17, 2);


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: cc1; Owner: carbonara
--

INSERT INTO usuarios VALUES (8, '11952572', 'cesar', '6f597c1ddab467f7bf5498aad1b41899', 'main', 1, '2010-08-21', true, 1, '127.0.0.1');
INSERT INTO usuarios VALUES (1, '000000000', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'main', 1, '2010-07-31', false, NULL, NULL);
INSERT INTO usuarios VALUES (2, '111111111', 'p4a', '66f03bee34f677db5c3c56b28343bcc9', 'crearusuarios', 2, '2010-07-31', false, NULL, NULL);


SET search_path = encuestas, pg_catalog;

--
-- Data for Name: censo; Type: TABLE DATA; Schema: encuestas; Owner: carbonara
--


--
-- Data for Name: habitantes; Type: TABLE DATA; Schema: encuestas; Owner: carbonara
--


SET search_path = public, pg_catalog;

--
-- Data for Name: auditor; Type: TABLE DATA; Schema: public; Owner: carbonara
--

INSERT INTO auditor VALUES (8, 'INSERT', '2010-10-22 01:47:22.904162-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:47:22.969344-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:48:38.3144-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:48:38.34156-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:51:31.189758-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:05.569571-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:05.597957-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:26.674978-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:43.886653-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:43.91685-04:30', 'tabla1', '127.0.0.1', '1');
INSERT INTO auditor VALUES (8, 'UPDATE', '2010-10-22 01:52:49.450928-04:30', 'tabla1', '127.0.0.1', '1');


SET search_path = cc1, pg_catalog;

--
-- Name: crear_usuarios_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY crear_usuarios
    ADD CONSTRAINT crear_usuarios_pkey PRIMARY KEY (cedula);


--
-- Name: habitante_ukey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla20
    ADD CONSTRAINT habitante_ukey UNIQUE (tabla20_campo2);


--
-- Name: menu_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_pkey PRIMARY KEY (id);


--
-- Name: tabla10_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla10
    ADD CONSTRAINT tabla10_pkey PRIMARY KEY (tabla10_campo1);


--
-- Name: tabla11_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla11
    ADD CONSTRAINT tabla11_pkey PRIMARY KEY (tabla11_campo1);


--
-- Name: tabla12_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla12
    ADD CONSTRAINT tabla12_pkey PRIMARY KEY (tabla12_campo1);


--
-- Name: tabla13_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla13
    ADD CONSTRAINT tabla13_pkey PRIMARY KEY (tabla13_campo1);


--
-- Name: tabla14_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla14
    ADD CONSTRAINT tabla14_pkey PRIMARY KEY (tabla14_campo1);


--
-- Name: tabla15_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla15
    ADD CONSTRAINT tabla15_pkey PRIMARY KEY (tabla15_campo1);


--
-- Name: tabla16_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla16
    ADD CONSTRAINT tabla16_pkey PRIMARY KEY (tabla16_campo1);


--
-- Name: tabla17_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla17
    ADD CONSTRAINT tabla17_pkey PRIMARY KEY (tabla17_campo1);


--
-- Name: tabla18_pkey1; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla18
    ADD CONSTRAINT tabla18_pkey1 PRIMARY KEY (tabla18_campo1);


--
-- Name: tabla19_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla19
    ADD CONSTRAINT tabla19_pkey PRIMARY KEY (tabla19_campo1);


--
-- Name: tabla1_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla0
    ADD CONSTRAINT tabla1_pkey PRIMARY KEY (tabla1_campo1);


--
-- Name: tabla1_pkey1; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla1
    ADD CONSTRAINT tabla1_pkey1 PRIMARY KEY (tabla1_campo1);


--
-- Name: tabla20_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla20
    ADD CONSTRAINT tabla20_pkey PRIMARY KEY (tabla20_campo1);


--
-- Name: tabla21_pkey1; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla21
    ADD CONSTRAINT tabla21_pkey1 PRIMARY KEY (tabla21_campo1);


--
-- Name: tabla22_pkey1; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla22
    ADD CONSTRAINT tabla22_pkey1 PRIMARY KEY (tabla22_campo1);


--
-- Name: tabla2_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla2
    ADD CONSTRAINT tabla2_pkey PRIMARY KEY (tabla2_campo1);


--
-- Name: tabla3_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla3
    ADD CONSTRAINT tabla3_pkey PRIMARY KEY (tabla3_campo1);


--
-- Name: tabla4_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla4
    ADD CONSTRAINT tabla4_pkey PRIMARY KEY (tabla4_campo1);


--
-- Name: tabla5_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla5
    ADD CONSTRAINT tabla5_pkey PRIMARY KEY (tabla5_campo1);


--
-- Name: tabla6_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla6
    ADD CONSTRAINT tabla6_pkey PRIMARY KEY (tabla6_campo1);


--
-- Name: tabla7_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla7
    ADD CONSTRAINT tabla7_pkey PRIMARY KEY (tabla7_campo1);


--
-- Name: tabla8_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla8
    ADD CONSTRAINT tabla8_pkey PRIMARY KEY (tabla8_campo1);


--
-- Name: tabla9_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY tabla9
    ADD CONSTRAINT tabla9_pkey PRIMARY KEY (tabla9_campo1);


--
-- Name: usuario_menu_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY usuario_menu
    ADD CONSTRAINT usuario_menu_pkey PRIMARY KEY (id_usuario, id_menu);


--
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: cc1; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


SET search_path = encuestas, pg_catalog;

--
-- Name: censo_fkey; Type: CONSTRAINT; Schema: encuestas; Owner: carbonara; Tablespace:
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_fkey PRIMARY KEY (id);


SET search_path = cc1, pg_catalog;

--
-- Name: auditor; Type: TRIGGER; Schema: cc1; Owner: carbonara
--

CREATE TRIGGER auditor
    AFTER INSERT OR DELETE OR UPDATE ON tabla1
    FOR EACH ROW
    EXECUTE PROCEDURE public.auditoria();


SET search_path = encuestas, pg_catalog;

--
-- Name: auditor; Type: TRIGGER; Schema: encuestas; Owner: carbonara
--

CREATE TRIGGER auditor
    AFTER INSERT OR DELETE OR UPDATE ON censo
    FOR EACH ROW
    EXECUTE PROCEDURE public.auditoria();


SET search_path = cc1, pg_catalog;

--
-- Name: fk_menu; Type: FK CONSTRAINT; Schema: cc1; Owner: carbonara
--

ALTER TABLE ONLY usuario_menu
    ADD CONSTRAINT fk_menu FOREIGN KEY (id_menu) REFERENCES menu(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_usuario; Type: FK CONSTRAINT; Schema: cc1; Owner: carbonara
--

ALTER TABLE ONLY usuario_menu
    ADD CONSTRAINT fk_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE;


SET search_path = encuestas, pg_catalog;

--
-- Name: censo_estado_civil_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_estado_civil_fkey FOREIGN KEY (estado_civil) REFERENCES cc1.tabla22(tabla22_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_grado_instruccion_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_grado_instruccion_fkey FOREIGN KEY (grado_instruccion) REFERENCES cc1.tabla1(tabla1_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_ocupacion_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_ocupacion_fkey FOREIGN KEY (ocupacion) REFERENCES cc1.tabla2(tabla2_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_parentesco_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_parentesco_fkey FOREIGN KEY (parentesco) REFERENCES cc1.tabla4(tabla4_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_profesion_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_profesion_fkey FOREIGN KEY (profesion) REFERENCES cc1.tabla3(tabla3_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_sector_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_sector_fkey FOREIGN KEY (sector) REFERENCES cc1.tabla13(tabla13_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_situacion_laboral_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_situacion_laboral_fkey FOREIGN KEY (situacion_laboral) REFERENCES cc1.tabla18(tabla18_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: censo_vivienda_fkey; Type: FK CONSTRAINT; Schema: encuestas; Owner: carbonara
--

ALTER TABLE ONLY censo
    ADD CONSTRAINT censo_vivienda_fkey FOREIGN KEY (vivienda) REFERENCES cc1.tabla14(tabla14_campo1) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: cc1; Type: ACL; Schema: -; Owner: carbonara
--

REVOKE ALL ON SCHEMA cc1 FROM PUBLIC;
REVOKE ALL ON SCHEMA cc1 FROM carbonara;
GRANT ALL ON SCHEMA cc1 TO carbonara;
GRANT ALL ON SCHEMA cc1 TO postgres;
GRANT ALL ON SCHEMA cc1 TO PUBLIC;


--
-- Name: encuestas; Type: ACL; Schema: -; Owner: carbonara
--

REVOKE ALL ON SCHEMA encuestas FROM PUBLIC;
REVOKE ALL ON SCHEMA encuestas FROM carbonara;
GRANT ALL ON SCHEMA encuestas TO carbonara;
GRANT USAGE ON SCHEMA encuestas TO consultor;


--
-- Name: public; Type: ACL; Schema: -; Owner: carbonara
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM carbonara;
GRANT ALL ON SCHEMA public TO carbonara;
GRANT ALL ON SCHEMA public TO postgres;
GRANT USAGE ON SCHEMA public TO consultor;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: habitantes; Type: ACL; Schema: encuestas; Owner: carbonara
--

REVOKE ALL ON TABLE habitantes FROM PUBLIC;
REVOKE ALL ON TABLE habitantes FROM carbonara;
GRANT ALL ON TABLE habitantes TO carbonara;
GRANT SELECT ON TABLE habitantes TO consultor;


--
-- PostgreSQL database dump complete
--

