CREATE OR REPLACE FUNCTION update_passwd(hash text, account text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    res integer;
BEGIN
    UPDATE virtual_users SET password = hash WHERE email = account RETURNING id INTO res;
    RETURN res;
END;
$$;