--
-- PostgreSQL database dump
--

-- Dumped from database version 15.1
-- Dumped by pg_dump version 15.1

-- Started on 2023-01-14 08:06:43

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE koryto_studenta;
--
-- TOC entry 3404 (class 1262 OID 16398)
-- Name: koryto_studenta; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE koryto_studenta WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Polish_Poland.1250';


ALTER DATABASE koryto_studenta OWNER TO postgres;

\connect koryto_studenta

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 245 (class 1255 OID 16519)
-- Name: createuser(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.createuser(loginv character varying, hashv character varying, emailv character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $$    
BEGIN    
  LOCK TABLE "User" IN ACCESS EXCLUSIVE MODE;
  IF EXISTS(SELECT U.login FROM "User" U WHERE U.login = loginV) THEN
      return 0;
  END IF;

  INSERT INTO "User"(login, hash, email) VALUES(loginV, hashV, emailV);
RETURN 1;
END;   
$$;


ALTER FUNCTION public.createuser(loginv character varying, hashv character varying, emailv character varying) OWNER TO postgres;

--
-- TOC entry 243 (class 1255 OID 16526)
-- Name: dislikerecipe(integer, integer); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.dislikerecipe(IN recipe_idv integer, IN user_idv integer)
    LANGUAGE plpgsql
    AS $$
begin
	if  exists(SELECT * FROM "FAV_RECIPES" WHERE recipe_id = recipe_idV and user_id =  user_idV FOR UPDATE) then
		UPDATE "Recipe" SET likes = likes - 1 WHERE id = recipe_idV;
		DELETE FROM "FAV_RECIPES" WHERE recipe_id = recipe_idV and user_id =  user_idV; 
	END IF;
end
$$;


ALTER PROCEDURE public.dislikerecipe(IN recipe_idv integer, IN user_idv integer) OWNER TO postgres;

--
-- TOC entry 244 (class 1255 OID 16525)
-- Name: likerecipe(integer, integer); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.likerecipe(IN recipe_idv integer, IN user_idv integer)
    LANGUAGE plpgsql
    AS $$
begin
	IF not EXISTS(SELECT * FROM "FAV_RECIPES" WHERE recipe_id = recipe_idV and user_id = user_idV FOR UPDATE) then
		UPDATE "Recipe" SET likes = likes + 1 WHERE id = recipe_idV;
		INSERT INTO "FAV_RECIPES"(recipe_id, user_id) VALUES (recipe_idV, user_idV); 
	END IF;
end
$$;


ALTER PROCEDURE public.likerecipe(IN recipe_idv integer, IN user_idv integer) OWNER TO postgres;

--
-- TOC entry 230 (class 1255 OID 16487)
-- Name: login_validate(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.login_validate() RETURNS trigger
    LANGUAGE plpgsql
    AS $_$
BEGIN
IF NEW.login !~ '^[A-Za-z0-9]{5,30}$' THEN
raise exception 'Invalid login.';
END IF;

RETURN NEW;
END;
$_$;


ALTER FUNCTION public.login_validate() OWNER TO postgres;

--
-- TOC entry 242 (class 1255 OID 16507)
-- Name: recipeperuser(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.recipeperuser() RETURNS double precision
    LANGUAGE plpgsql
    AS $$
        DECLARE  creatorsNum  FLOAT DEFAULT (SELECT count(DISTINCT creator_id) FROM RECIPES);
        recipesNum  FLOAT DEFAULT (SELECT count(id) FROM RECIPES);

          BEGIN

                  IF creatorsNum = 0 then
                    return  0;
                  END IF;

                  RETURN recipesNum / creatorsNum;
          END;
  $$;


ALTER FUNCTION public.recipeperuser() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 224 (class 1259 OID 16454)
-- Name: FAV_RECIPES; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."FAV_RECIPES" (
    user_id integer NOT NULL,
    recipe_id integer NOT NULL
);


ALTER TABLE public."FAV_RECIPES" OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16453)
-- Name: FAV_RECIPES_recipe_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."FAV_RECIPES_recipe_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FAV_RECIPES_recipe_id_seq" OWNER TO postgres;

--
-- TOC entry 3405 (class 0 OID 0)
-- Dependencies: 223
-- Name: FAV_RECIPES_recipe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."FAV_RECIPES_recipe_id_seq" OWNED BY public."FAV_RECIPES".recipe_id;


--
-- TOC entry 222 (class 1259 OID 16452)
-- Name: FAV_RECIPES_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."FAV_RECIPES_user_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."FAV_RECIPES_user_id_seq" OWNER TO postgres;

--
-- TOC entry 3406 (class 0 OID 0)
-- Dependencies: 222
-- Name: FAV_RECIPES_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."FAV_RECIPES_user_id_seq" OWNED BY public."FAV_RECIPES".user_id;


--
-- TOC entry 227 (class 1259 OID 16473)
-- Name: LOGS; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."LOGS" (
    id integer NOT NULL,
    browser character varying(60) DEFAULT 'unknown'::character varying,
    ip character varying(39) DEFAULT '0.0.0.0'::character varying,
    login_date date NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public."LOGS" OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16471)
-- Name: LOGS_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."LOGS_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."LOGS_id_seq" OWNER TO postgres;

--
-- TOC entry 3407 (class 0 OID 0)
-- Dependencies: 225
-- Name: LOGS_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."LOGS_id_seq" OWNED BY public."LOGS".id;


--
-- TOC entry 226 (class 1259 OID 16472)
-- Name: LOGS_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."LOGS_user_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."LOGS_user_id_seq" OWNER TO postgres;

--
-- TOC entry 3408 (class 0 OID 0)
-- Dependencies: 226
-- Name: LOGS_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."LOGS_user_id_seq" OWNED BY public."LOGS".user_id;


--
-- TOC entry 221 (class 1259 OID 16428)
-- Name: Recipe; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Recipe" (
    id integer NOT NULL,
    category_id integer NOT NULL,
    name character varying(50) NOT NULL,
    description text NOT NULL,
    ingridients text NOT NULL,
    prep_time smallint DEFAULT 25 NOT NULL,
    ingr_num smallint DEFAULT 5 NOT NULL,
    likes bigint DEFAULT 0 NOT NULL,
    creator_id integer NOT NULL,
    photo_path character varying(50) DEFAULT '1'::character varying NOT NULL
);


ALTER TABLE public."Recipe" OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16407)
-- Name: RecipeCategory; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."RecipeCategory" (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public."RecipeCategory" OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 16406)
-- Name: RecipeCategory_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."RecipeCategory_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."RecipeCategory_id_seq" OWNER TO postgres;

--
-- TOC entry 3409 (class 0 OID 0)
-- Dependencies: 216
-- Name: RecipeCategory_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."RecipeCategory_id_seq" OWNED BY public."RecipeCategory".id;


--
-- TOC entry 219 (class 1259 OID 16426)
-- Name: Recipe_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Recipe_category_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Recipe_category_id_seq" OWNER TO postgres;

--
-- TOC entry 3410 (class 0 OID 0)
-- Dependencies: 219
-- Name: Recipe_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Recipe_category_id_seq" OWNED BY public."Recipe".category_id;


--
-- TOC entry 220 (class 1259 OID 16427)
-- Name: Recipe_creator_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Recipe_creator_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Recipe_creator_id_seq" OWNER TO postgres;

--
-- TOC entry 3411 (class 0 OID 0)
-- Dependencies: 220
-- Name: Recipe_creator_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Recipe_creator_id_seq" OWNED BY public."Recipe".creator_id;


--
-- TOC entry 218 (class 1259 OID 16425)
-- Name: Recipe_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Recipe_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Recipe_id_seq" OWNER TO postgres;

--
-- TOC entry 3412 (class 0 OID 0)
-- Dependencies: 218
-- Name: Recipe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Recipe_id_seq" OWNED BY public."Recipe".id;


--
-- TOC entry 215 (class 1259 OID 16400)
-- Name: User; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."User" (
    id integer NOT NULL,
    login character varying(30) NOT NULL,
    hash character varying(72) NOT NULL,
    email character varying(50) NOT NULL
);


ALTER TABLE public."User" OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 16399)
-- Name: User_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."User_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."User_id_seq" OWNER TO postgres;

--
-- TOC entry 3413 (class 0 OID 0)
-- Dependencies: 214
-- Name: User_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."User_id_seq" OWNED BY public."User".id;


--
-- TOC entry 229 (class 1259 OID 16512)
-- Name: recipe_creator; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.recipe_creator AS
 SELECT u.login,
    r.name
   FROM (public."Recipe" r
     JOIN public."User" u ON ((u.id = r.creator_id)));


ALTER TABLE public.recipe_creator OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16508)
-- Name: user_fav_recipes; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.user_fav_recipes AS
 SELECT u.login,
    r.name
   FROM ((public."FAV_RECIPES" fr
     JOIN public."User" u ON ((u.id = fr.user_id)))
     JOIN public."Recipe" r ON ((fr.recipe_id = r.id)));


ALTER TABLE public.user_fav_recipes OWNER TO postgres;

--
-- TOC entry 3219 (class 2604 OID 16457)
-- Name: FAV_RECIPES user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FAV_RECIPES" ALTER COLUMN user_id SET DEFAULT nextval('public."FAV_RECIPES_user_id_seq"'::regclass);


--
-- TOC entry 3220 (class 2604 OID 16458)
-- Name: FAV_RECIPES recipe_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FAV_RECIPES" ALTER COLUMN recipe_id SET DEFAULT nextval('public."FAV_RECIPES_recipe_id_seq"'::regclass);


--
-- TOC entry 3221 (class 2604 OID 16476)
-- Name: LOGS id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."LOGS" ALTER COLUMN id SET DEFAULT nextval('public."LOGS_id_seq"'::regclass);


--
-- TOC entry 3224 (class 2604 OID 16479)
-- Name: LOGS user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."LOGS" ALTER COLUMN user_id SET DEFAULT nextval('public."LOGS_user_id_seq"'::regclass);


--
-- TOC entry 3212 (class 2604 OID 16431)
-- Name: Recipe id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe" ALTER COLUMN id SET DEFAULT nextval('public."Recipe_id_seq"'::regclass);


--
-- TOC entry 3213 (class 2604 OID 16432)
-- Name: Recipe category_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe" ALTER COLUMN category_id SET DEFAULT nextval('public."Recipe_category_id_seq"'::regclass);


--
-- TOC entry 3217 (class 2604 OID 16436)
-- Name: Recipe creator_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe" ALTER COLUMN creator_id SET DEFAULT nextval('public."Recipe_creator_id_seq"'::regclass);


--
-- TOC entry 3211 (class 2604 OID 16410)
-- Name: RecipeCategory id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."RecipeCategory" ALTER COLUMN id SET DEFAULT nextval('public."RecipeCategory_id_seq"'::regclass);


--
-- TOC entry 3210 (class 2604 OID 16403)
-- Name: User id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User" ALTER COLUMN id SET DEFAULT nextval('public."User_id_seq"'::regclass);


--
-- TOC entry 3395 (class 0 OID 16454)
-- Dependencies: 224
-- Data for Name: FAV_RECIPES; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (13, 16);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (13, 15);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (13, 14);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (14, 16);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (14, 15);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (14, 17);
INSERT INTO public."FAV_RECIPES" (user_id, recipe_id) VALUES (14, 19);


--
-- TOC entry 3398 (class 0 OID 16473)
-- Dependencies: 227
-- Data for Name: LOGS; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3392 (class 0 OID 16428)
-- Dependencies: 221
-- Data for Name: Recipe; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (16, 3, 'CARBONARA', 'Makaron ugotować al dente w osolonej wodzie. Jajka sparzyć wrzątkiem, wbić do głębokiego talerza, doprawić solą i roztrzepać widelcem.
Na patelnię włożyć pokrojony w kosteczkę boczek i podsmażyć na małym ogniu przez kilka minut aż się lekko zrumieni. Dodać starty czosnek oraz posiekaną natkę pietruszki i smażyć jeszcze przez kilkanaście sekund.
Trzymając patelnię na małym ogniu dodać makaron i wymieszać. Odstawić z ognia, dodać połowę sera i doprawić świeżo zmielonym pieprzem.
Makaron polać roztrzepanymi jajkami i wymieszać. Jajka nie mogą całkowicie się ściąć, mają utworzyć kremowy sos i tylko trochę zgęstnieć od gorącego makaronu.
Wyłożyć na talerze i posypać pozostałym serem.', '2 jajka
100 g boczku (wędzonego lub suszonego - pancetty)
150 g makaronu spaghetti
2 ząbki czosnku
2 łyżki drobno posiekanej natki pietruszki
30 g sera Pecorino (lub Parmezanu lub Grana Padano)
sól i świeżo zmielony czarny pieprz', 25, 7, 2, 13, '13.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (19, 1, 'BURGER CHEESE', '1
Pomidory umyć, przepołowić, usunąć pestki i pokroić w drobną kostkę.
2
Cebulę i czosnek obrać, drobno posiekać, wymieszać z keczupem i pokrojonymi w kostkę pomidorami.
3
Kotlety do hamburgerów przez ok. 2 minuty podsmażyć na rozgrzanym oleju, obrócić na drugą stronę, posypać serem i pod przykryciem na średnim ogniu smażyć kolejne 3-5 minut, aż ser się rozpuści.
4
Bułki do hamburgerów rozłożyć i od wewnętrznej strony opiec je na tosterze lub na patelni. Papryczki jalapeňos osączyć.
5
Każdą połówkę bułki posmarować 1 łyżeczką majonezu. Na dolnych częściach bułek ułożyć liście sałaty, papryczki jalapeňos i kotlety, na nich położyć salsę pomidorową i przykryć górnymi połówkami bułki.
6
Hamburgery można podawać z frytkami.', '
2
pomidory
1
czerwona cebula
1
ząbek czosnku
1 łyżka
keczupu
2
kotlety do hamburgerów 180 g każdy
1 łyżka
oleju
100 g
tartego sera cheddar
2
bułki do hamburgerów
40 g
zielonych papryczek jalapeňos ze słoika
4 łyżeczki
majonez
kilka liści
sałaty', 25, 10, 1, 13, '16.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (13, 4, 'POMIDOROWA', '1 - W jednym garnku umieść razem: około kilograma mięsa (tylko z kurczaka lub z dodatkiem wołowiny z kością); dwie obrane marchewki; korzeń pietruszki; cebulę; kawałek korzenia selera. Dodaj też dwa ziarna ziela angielskiego, listek laurowy, łyżeczkę soli oraz pół łyżeczki pieprzu. Wlej 1500 ml wody. Garnek przykryj przykrywką i zagotuj zupę. Zmniejsz moc palnika do takiej, by zupa tylko mrugała i gotuj ją przez 90 minut - jeśli dodany był tylko kurczak, lub 120 minut - jeśli użyta była też wołowina z kością.

2 - Po dwóch godzinach z brzegów garnka usuń szumowiny. Przy pomocy cedzaka wyłów z zupy całe mięso, warzywa i przyprawy. Powinno zostać około 1200 ml bulionu. Jeśli odparowało więcej wywaru, to ubytek uzupełnij wrzątkiem. Warzywa i mięso z rosołu można zmielić i wykorzystać do zrobienia pasztetu, czy też jako farsz do pierogów lub naleśników. 
', '1 kg mięsa na rosół: u mnie 2 ćwiartki kurczaka i gicz wołowa z kością*
2 średnie marchewki - około 280 g
1 mały korzeń pietruszki - około 90 g
kawałek korzenia selera - około 80 g
1 mała cebula - około 100 g
1500 ml wody - z kranu lub filtrowana
przyprawy i zioła: 2 ziarna ziela angielskiego; 1 listek laurowy; łyżeczka soli; pół łyżeczki pieprzu', 60, 5, 0, 13, '10.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (18, 1, 'BURGER BBQ', 'Cebulę posiekać i zeszklić na patelni
Odłożyć do ostygnięcia
Mięso włożyć do miski
Dodać ketchup i musztardę oraz doprawić solą i pieprzem
Dosypać cebulę i wymieszać
Rozdzielić mięso na dwie części i uformować kotlety
Jeśli będziecie robić na grillu to kratkę najlepiej posmarować oliwą (nie piec na tacce), jeśli na patelni grillowej to najpierw należy na niej rozgrzać oliwę
Kotlety położyć na gorącej patelni lub grillu i smażyć około 5 minut z każdej strony (na grillu lepiej przekładać mięso tak co 3 minuty żeby się nie przypaliło) - będą "medium"
Bułki przekroić na pół i położyć wewnętrznymi częściami na grillu (lub wlożyć na chwilę do piekarnika rozgrzanego do 150 stopni)
Na dolnej części bułki ułożyć sałatę a na niej boczek', '250 g mięsa mielonego wołowego
1 cebula
2 szczypty soli
pół łyżeczki mielonego pieprzu
1½ łyżki ketchupu

1 łyżka musztardy sarepskiej
2 szt. bułki do hamburgerów
2 liście sałaty masłowej
4 plasterki wędzonego boczku
oliwa z oliwek (do smażenia)
2 plasterki sera żółtego
2 łyżki sosu barbeque
2 szt. ogórka kiszonego', 60, 15, 0, 13, '15.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (15, 4, 'PIEROGI RUSKIE', 'Ziemniaki obrać, opłukać, włożyć do garnka, dodać sól, przykryć zimną wodą i zagotować. Gotować pod uchyloną pokrywą przez około pół godziny lub do miękkości. 
Odcedzić, włożyć z powrotem do garnka i jeszcze gorące roztłuc dokładnie tłuczkiem do ziemniaków na gładką masę bez grudek. Ziemniaki całkowicie ostudzić, odparować.
Twaróg pokruszyć, rozgnieść widelcem lub praską (sera nie mielimy w maszynce bo nadzienie wyjdzie za rzadkie). Wymieszać z ziemniakami, doprawić solą i pieprzem.
Cebulkę (jeśli dodajemy) pokroić w kostkę i zeszklić na maśle lub smalcu, dodać do nadzienia, wymieszać.
', '500 g twarogu
500 g ziemniaków
ok. 2 łyżeczki soli
1/2 łyżeczki zmielonego pieprzu ziołowego lub czarnego
opcjonalnie: 1 mała cebula', 110, 12, 2, 13, '12.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (14, 4, 'KOPERKOWA', 'Szklanka ma u mnie pojemność 250 ml. 
Warzywa ważone były przed ewentualnym obraniem/przygotowaniem.
Do ugotowania zupy polecam większy garnek o pojemności minimum 3 litrów. 

Kalorie policzone zostały na podstawie użytych przeze mnie składników. Jest to więc orientacyjna ilość kalorii, ponieważ nawet Twoja śmietanka lub bulion mogą mieć inną ilość kalorii niż te, których użyłam ja. Z podanej ilości składników otrzymasz około 1300 ml zupy koperkowej.

Zupa koperkowa kusi mnie za każdym razem, gdy widzę u mnie na parapecie pęczek koperku. Od maja cała moja kuchnia obstawiona jest świeżymi ziołami oraz koszami z warzywami i owocami. W ogródku przed oknem kuchennym rosną też zioła, które lubię mieć na bieżąco w większych ilościach. Zawsze jest to oregano, które jest tak mocne, że zimuje i co rok pięknie rośnie bez dosadzania. Moja ukochana kolendra, szczypiorek.. Uf, trochę się rozpędziłam. Może zacznijmy już szykować zupkę :-)

', '1 litr bulionu warzywnego lub drobiowego
spory pęczek koperku
3 ziemniaki - 280 g
3 młode marchewki - 150 g
1 dymka ze szczypiorkiem
2 łyżki masła klarowanego
4 łyżki śmietanki kremówki 30 %
przyprawy: płaska łyżeczka soli, po 1/4 łyżeczki gałki muszkatołowej oraz oregano, szczypta kurkumy', 60, 8, 1, 13, '11.jpg');
INSERT INTO public."Recipe" (id, category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) VALUES (17, 3, 'BOLOGNESE', 'Boczek pokroić w drobną kostkę i włożyć na dużą patelnię, wytopić na małym ogniu aż się zrumieni. Przesunąć na bok, w wytopiony tłuszcz włożyć drobno posiekaną cebulę i zeszklić. Dodać drobno posiekany seler naciowy i startą marchewkę. Obsmażyć, następnie wszystko przełożyć do garnka.
Na patelnię wlać oliwę i obsmażyć mięso. Obsmażone mięso przełożyć do garnka z boczkiem i warzywami. Wlać wino i gotować mieszając ok. 5 minut. Dodać gorący bulion i koncentrat pomidorowy, wymieszać. Dodać pomidory z puszki, doprawić solą i pieprzem. Drewnianą łyżką rozdrobnić pomidory i wymieszać.
Przykryć i gotować na małym ogniu przez minimum 2 godziny, od czasu do czasu mieszając. W połowie gotowania dodać mleko. Podawać z ugotowanym makaronem spaghetti posypując tartym parmezanem.', '150 g boczku*
1 cebula
2 łodygi selera naciowego
1 marchewka
2 łyżki oliwy
500 g mielonego mięsa**
1 szklanka czerwonego wina
1 szklanka gorącego bulionu
4 łyżki koncentratu pomidorowego
1 puszka obranych pomidorów
1/2 szklanki mleka
makaron spaghetti (75 g / porcję)
tarty parmezan', 45, 10, 1, 13, '14.jpg');


--
-- TOC entry 3388 (class 0 OID 16407)
-- Dependencies: 217
-- Data for Name: RecipeCategory; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."RecipeCategory" (id, name) VALUES (1, 'BURGER');
INSERT INTO public."RecipeCategory" (id, name) VALUES (2, 'KEBAB');
INSERT INTO public."RecipeCategory" (id, name) VALUES (3, 'PASTA');
INSERT INTO public."RecipeCategory" (id, name) VALUES (4, 'POLISH');
INSERT INTO public."RecipeCategory" (id, name) VALUES (5, 'SUSHI');
INSERT INTO public."RecipeCategory" (id, name) VALUES (6, 'ITALIAN');
INSERT INTO public."RecipeCategory" (id, name) VALUES (7, 'PIZZA');


--
-- TOC entry 3386 (class 0 OID 16400)
-- Dependencies: 215
-- Data for Name: User; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."User" (id, login, hash, email) VALUES (13, 'mik155', '$2y$10$nSw9eVBn/Ygf1KFuV88.aOzsGIHZ5vmNpxTV9VlMfpZcY4gDq.Ejq', 'mik155@wp.pl');
INSERT INTO public."User" (id, login, hash, email) VALUES (14, 'kucharz123', '$2y$10$lJ3vqjTJSdbAU6vE1MF07Ok/K3Bmtlltnjz4x0kaypYN0fkg5zBnu', 'kucharz@gmail.com');


--
-- TOC entry 3414 (class 0 OID 0)
-- Dependencies: 223
-- Name: FAV_RECIPES_recipe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."FAV_RECIPES_recipe_id_seq"', 1, false);


--
-- TOC entry 3415 (class 0 OID 0)
-- Dependencies: 222
-- Name: FAV_RECIPES_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."FAV_RECIPES_user_id_seq"', 1, false);


--
-- TOC entry 3416 (class 0 OID 0)
-- Dependencies: 225
-- Name: LOGS_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."LOGS_id_seq"', 1, false);


--
-- TOC entry 3417 (class 0 OID 0)
-- Dependencies: 226
-- Name: LOGS_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."LOGS_user_id_seq"', 1, false);


--
-- TOC entry 3418 (class 0 OID 0)
-- Dependencies: 216
-- Name: RecipeCategory_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."RecipeCategory_id_seq"', 7, true);


--
-- TOC entry 3419 (class 0 OID 0)
-- Dependencies: 219
-- Name: Recipe_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Recipe_category_id_seq"', 1, false);


--
-- TOC entry 3420 (class 0 OID 0)
-- Dependencies: 220
-- Name: Recipe_creator_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Recipe_creator_id_seq"', 1, false);


--
-- TOC entry 3421 (class 0 OID 0)
-- Dependencies: 218
-- Name: Recipe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Recipe_id_seq"', 19, true);


--
-- TOC entry 3422 (class 0 OID 0)
-- Dependencies: 214
-- Name: User_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."User_id_seq"', 14, true);


--
-- TOC entry 3232 (class 2606 OID 16460)
-- Name: FAV_RECIPES FAV_RECIPES_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FAV_RECIPES"
    ADD CONSTRAINT "FAV_RECIPES_pkey" PRIMARY KEY (user_id, recipe_id);


--
-- TOC entry 3234 (class 2606 OID 16481)
-- Name: LOGS LOGS_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."LOGS"
    ADD CONSTRAINT "LOGS_pkey" PRIMARY KEY (id);


--
-- TOC entry 3228 (class 2606 OID 16412)
-- Name: RecipeCategory RecipeCategory_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."RecipeCategory"
    ADD CONSTRAINT "RecipeCategory_pkey" PRIMARY KEY (id);


--
-- TOC entry 3230 (class 2606 OID 16441)
-- Name: Recipe Recipe_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe"
    ADD CONSTRAINT "Recipe_pkey" PRIMARY KEY (id);


--
-- TOC entry 3226 (class 2606 OID 16405)
-- Name: User User_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User"
    ADD CONSTRAINT "User_pkey" PRIMARY KEY (id);


--
-- TOC entry 3240 (class 2620 OID 16488)
-- Name: User login_validate; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER login_validate BEFORE INSERT ON public."User" FOR EACH ROW EXECUTE FUNCTION public.login_validate();


--
-- TOC entry 3235 (class 2606 OID 16442)
-- Name: Recipe category_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe"
    ADD CONSTRAINT category_fk FOREIGN KEY (category_id) REFERENCES public."RecipeCategory"(id);


--
-- TOC entry 3236 (class 2606 OID 16447)
-- Name: Recipe creator_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Recipe"
    ADD CONSTRAINT creator_fk FOREIGN KEY (creator_id) REFERENCES public."User"(id);


--
-- TOC entry 3237 (class 2606 OID 16461)
-- Name: FAV_RECIPES recipe_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FAV_RECIPES"
    ADD CONSTRAINT recipe_fk FOREIGN KEY (recipe_id) REFERENCES public."Recipe"(id);


--
-- TOC entry 3238 (class 2606 OID 16466)
-- Name: FAV_RECIPES user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."FAV_RECIPES"
    ADD CONSTRAINT user_fk FOREIGN KEY (user_id) REFERENCES public."User"(id);


--
-- TOC entry 3239 (class 2606 OID 16482)
-- Name: LOGS user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."LOGS"
    ADD CONSTRAINT user_fk FOREIGN KEY (user_id) REFERENCES public."User"(id) NOT VALID;


-- Completed on 2023-01-14 08:06:44

--
-- PostgreSQL database dump complete
--

