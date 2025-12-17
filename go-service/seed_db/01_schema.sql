-- Minimal schema required for Node students module (read endpoints)

CREATE TABLE IF NOT EXISTS roles (
    id   INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
   id                      BIGSERIAL PRIMARY KEY,
   name                    TEXT NOT NULL,
   email                   TEXT NOT NULL,
   role_id                 INTEGER NOT NULL REFERENCES roles(id),
    password                TEXT NULL,
    is_email_verified       BOOLEAN NOT NULL DEFAULT TRUE,
    last_login              TIMESTAMPTZ NULL,
    is_active               BOOLEAN NOT NULL DEFAULT TRUE,
    reporter_id             BIGINT NULL REFERENCES users(id),
    status_last_reviewed_dt TIMESTAMPTZ NULL,
    status_last_reviewer_id BIGINT NULL REFERENCES users(id),
    updated_dt              TIMESTAMPTZ NULL
    );

CREATE TABLE IF NOT EXISTS user_profiles (
    user_id              BIGINT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    phone                TEXT NULL,
    gender               TEXT NULL,
    dob                  DATE NULL,
    class_name           TEXT NULL,
    section_name         TEXT NULL,
    roll                 INTEGER NULL,
    father_name          TEXT NULL,
    father_phone         TEXT NULL,
    mother_name          TEXT NULL,
    mother_phone         TEXT NULL,
    guardian_name        TEXT NULL,
    guardian_phone       TEXT NULL,
    relation_of_guardian TEXT NULL,
    current_address      TEXT NULL,
    permanent_address    TEXT NULL,
    admission_dt         DATE NULL
    );

CREATE INDEX IF NOT EXISTS idx_users_role_id ON users(role_id);
