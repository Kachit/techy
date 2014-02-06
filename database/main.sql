------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------- USERS ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  user_id SERIAL PRIMARY KEY NOT NULL,
  email text NOT NULL,
  password char(40) NOT NULL,
  data text NOT NULL DEFAULT '',
  activated int DEFAULT 0,
  created int,
  updated int,
  deleted int,
  UNIQUE ( email )
);

CREATE OR REPLACE FUNCTION create_user(
  p_email text,
  p_password char(40),
  p_created int
)
RETURNS int AS $$
DECLARE
  p_user_id int;
BEGIN
    BEGIN
      INSERT INTO users
        ( email, password, created ) VALUES
        ( p_email, p_password, p_created )
        RETURNING user_id
        INTO p_user_id;
      RETURN p_user_id;
    EXCEPTION WHEN unique_violation THEN
      RETURN 0;
    END;
END;
$$
LANGUAGE plpgsql;

------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------- USERS ACTIVATION -------------------------------------------------
CREATE TABLE IF NOT EXISTS users_activations (
  user_id int PRIMARY KEY NOT NULL,
  code text NOT NULL
);

CREATE OR REPLACE FUNCTION create_activation_code(
  p_user_id int,
  p_code text
)
  RETURNS TABLE (
  user_id int,
  code text
  ) AS $$
BEGIN
    BEGIN
      INSERT INTO users_activations
        ( user_id, code ) VALUES
        ( p_user_id, p_code );
    EXCEPTION WHEN unique_violation THEN
    END;

    RETURN QUERY
      SELECT *
        FROM users_activations a
        WHERE a.user_id = p_user_id;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION activate(
  p_user_id int,
  p_code text,
  p_activated int
) RETURNS int AS $$
DECLARE
  r_user_id int;
BEGIN
    DELETE FROM users_activations
      WHERE ( user_id, code ) = ( p_user_id, p_code )
      RETURNING user_id
      INTO r_user_id;

    IF NOT FOUND THEN
      RETURN 0;
    END IF;

    UPDATE users
      SET activated = p_activated
      WHERE user_id = p_user_id;

    RETURN 1;
END;
$$
LANGUAGE plpgsql;

------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------- USERS RECOVERY ---------------------------------------------------
CREATE TABLE IF NOT EXISTS users_recovery_codes (
  user_id int PRIMARY KEY NOT NULL,
  code text NOT NULL,
  created int NOT NULL
);

CREATE OR REPLACE FUNCTION create_recovery_code(
  p_user_id int,
  p_code text,
  p_created int
)
  RETURNS TABLE (
  user_id int,
  code text
  ) AS $$
BEGIN
    BEGIN
      INSERT INTO users_recovery_codes
        ( user_id, code, created ) VALUES
        ( p_user_id, p_code, p_created );
    EXCEPTION WHEN unique_violation THEN
      UPDATE users_recovery_codes
        SET created = p_created
        WHERE user_id = p_user_id;
    END;

    RETURN QUERY
      SELECT *
        FROM users_recovery_codes a
        WHERE a.user_id = p_user_id;
END;
$$
LANGUAGE plpgsql;

------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------- ADMINS -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
  user_id SERIAL PRIMARY KEY NOT NULL,
  login text NOT NULL,
  password text NOT NULL,
  UNIQUE ( login )
);

CREATE OR REPLACE FUNCTION create_admin(
  p_login text,
  p_password char(40)
)
RETURNS int AS $$
DECLARE
  p_user_id int;
BEGIN
    BEGIN
      INSERT INTO admins
        ( login, password ) VALUES
        ( p_login, p_password )
        RETURNING user_id
        INTO p_user_id;
      RETURN p_user_id;
    EXCEPTION WHEN unique_violation THEN
      RETURN 0;
    END;
END;
$$
LANGUAGE plpgsql;

INSERT INTO admins ( login, password )
  VALUES ( 'admin', '8c22ebe38cef63fad2a0f509fdee2faac9a49464' );

-----------------------------------------------------------------------------------------------------------------------
----------------------------------------------------- ADMINS RECOVERY --------------------------------------------------
CREATE TABLE IF NOT EXISTS admins_recovery_codes (
  user_id int PRIMARY KEY NOT NULL,
  code text NOT NULL,
  created int NOT NULL
);

CREATE OR REPLACE FUNCTION create_admin_recovery_code(
  p_user_id int,
  p_code text,
  p_created int
)
RETURNS TABLE (
  user_id int,
  code text
) AS $$
BEGIN
    BEGIN
      INSERT INTO admins_recovery_codes
        ( user_id, code, created ) VALUES
        ( p_user_id, p_code, p_created );
    EXCEPTION WHEN unique_violation THEN
      UPDATE admins_recovery_codes a
        SET created = p_created
        WHERE a.user_id = p_user_id;
    END;

    RETURN QUERY
      SELECT a.user_id, a.code
        FROM admins_recovery_codes a
        WHERE a.user_id = p_user_id;
END;
$$
LANGUAGE plpgsql;
