CREATE TABLE app_contracts(
    app_contract_id INT AUTO_INCREMENT NOT NULL,
    date_of_due DATE NOT NULL,
    app_key VARCHAR(555) DEFAULT '',
    notice_days INT DEFAULT 15,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_app_contracts PRIMARY KEY (app_contract_id)
) ENGINE=InnoDB;

CREATE TABLE app_authorizations(
    app_authorization_id INT AUTO_INCREMENT NOT NULL,
    module VARCHAR(64) NOT NULL,
    action VARCHAR(64) DEFAULT '',
    description VARCHAR(64) DEFAULT '',
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_app_authorizations PRIMARY KEY (app_authorization_id)
) ENGINE=InnoDB;

CREATE TABLE user_roles(
    user_role_id INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(64) NOT NULL,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_user_roles PRIMARY KEY (user_role_id)
) ENGINE=InnoDB;

CREATE TABLE user_role_authorizations(
    user_role_id INT NOT NULL,
    app_authorization_id INT NOT NULL,
    CONSTRAINT fk_user_roles_authorization_user_roles FOREIGN KEY (user_role_id) REFERENCES user_roles (user_role_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_user_roles_authorization_app_authorizations FOREIGN KEY (app_authorization_id) REFERENCES app_authorizations (app_authorization_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE users(
    user_id INT AUTO_INCREMENT NOT NULL,
    user_name VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    -- gender VARCHAR(2) DEFAULT '2',
    gender enum('0','1','2') DEFAULT '2',
    avatar VARCHAR(64) DEFAULT '',
    email  VARCHAR(64) DEFAULT '' UNIQUE,
    user_role_id INT NOT NULL,
    phone  VARCHAR(32) DEFAULT '',
    is_verified TINYINT DEFAULT 0,
    date_verified DATETIME,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_user PRIMARY KEY (user_id),
    CONSTRAINT fk_user_user_roles FOREIGN KEY (user_role_id) REFERENCES user_roles (user_role_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE user_forgots(
    user_forgot_id INT AUTO_INCREMENT NOT NULL,
    user_id INT NOT NULL,
    secret_key VARCHAR(128) NOT NULL,
    used TINYINT DEFAULT 0,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_user_forgots PRIMARY KEY (user_forgot_id),
    CONSTRAINT fk_user_forgots_user FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO app_contracts(date_of_due,app_key,notice_days) VALUES ('2020-02-17','',15);
INSERT INTO app_authorizations (module,action,description,state)
VALUES ('home','home','dashboard',true),

       ('rol','listar','listar roles',true),
       ('rol','crear','crear nuevos rol',true),
       ('rol','eliminar','Eliminar un rol',true),
       ('rol','modificar','Acualizar los roles',true),

       ('user','listar','listar usuarios',true),
       ('user','crear','crear nuevo usuarios',true),
       ('user','eliminar','Eliminar un usuario',true),
       ('user','modificar','Acualizar los datos del usuario exepto la contraseña',true),
       ('user','modificarContraseña','Solo se permite actualizar la contraseña',true);

INSERT INTO user_roles (created_at, created_user_id, description, state)
VALUES ('2020-02-17 00:00:00', '0', 'Super Administrador', 1),
       ('2020-02-17 00:00:00', '0', 'Administrador', 1),
       ('2020-02-17 00:00:00', '0', 'Usuario', 1);

INSERT INTO users(user_name, password, full_name, avatar, email, user_role_id, gender)
VALUES ('yoel',sha1('cascadesheet'),'yoel','','yoel.antezana@gmail.com',1,2),
        ('admin1',sha1('admin1'),'admin1','','admin@admin.com',2,2);

INSERT INTO user_role_authorizations (user_role_id,app_authorization_id)
VALUES (1, 1),
       (1, 2),
       (1, 3),
       (1, 4),
       (1, 5),
       (1, 6),
       (1, 7),
       (1, 8),
       (1, 9),
       (1, 10);

INSERT INTO user_role_authorizations (user_role_id,app_authorization_id)
VALUES (2, 1),
       (2, 2),
       (2, 3),
       (2, 4),
       (2, 5),
       (2, 6),
       (2, 7),
       (2, 8),
       (2, 9),
       (2, 10);

INSERT INTO user_role_authorizations (user_role_id,app_authorization_id)
VALUES (3, 1),
       (3, 6),
       (3, 7),
       (3, 8),
       (3, 9),
       (3, 10);


-- CUSTOM SQL
CREATE TABLE companies(
    company_id INT AUTO_INCREMENT NOT NULL,
    document_number VARCHAR(32) DEFAULT '',
    social_reason VARCHAR(255) DEFAULT '',
    commercial_reason VARCHAR(255) DEFAULT '',
    representative VARCHAR(128) DEFAULT '',
    logo VARCHAR(128) DEFAULT '',
    logo_large VARCHAR(128) DEFAULT '',
    phone VARCHAR(32) DEFAULT '',
    email VARCHAR(64) DEFAULT '',
    fiscal_address VARCHAR(255) DEFAULT '',

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_companies PRIMARY KEY (company_id)
) ENGINE=InnoDB;

CREATE TABLE identity_document_types(
    code VARCHAR(1) NOT NULL,
    description VARCHAR(255) NOT NULL,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_identity_document_types PRIMARY KEY (code)
) ENGINE=InnoDB;

CREATE TABLE customers(
    customer_id INT AUTO_INCREMENT NOT NULL,

    document_number VARCHAR(16) NOT NULL,
    identity_document_code VARCHAR(64) NOT NULL,
    social_reason VARCHAR(255) DEFAULT '',
    commercial_reason VARCHAR(255) DEFAULT '',
    fiscal_address VARCHAR(255) DEFAULT '',
    email VARCHAR(64) DEFAULT '',
    telephone VARCHAR(255) DEFAULT '',

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_customers PRIMARY KEY (customer_id),
    CONSTRAINT fk_customers_identity_document_types FOREIGN KEY (identity_document_code) REFERENCES identity_document_types (code)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE plans(
    plan_id INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) DEFAULT '',
    speed VARCHAR(32) DEFAULT '',
    price FLOAT(8,2) DEFAULT 0.00,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_plans PRIMARY KEY (plan_id)
) ENGINE=InnoDB;

CREATE TABLE servers(
    server_id INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(128) DEFAULT '',
    address VARCHAR(64) DEFAULT '',

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    state TINYINT DEFAULT 1,
    CONSTRAINT pk_servers PRIMARY KEY (server_id)
) ENGINE=InnoDB;

CREATE TABLE contracts(
    contract_id INT AUTO_INCREMENT NOT NULL,
    datetime_of_issue DATE NOT NULL,
    datetime_of_due DATE NOT NULL,
    datetime_of_due_enable TINYINT DEFAULT 0,
    observation TEXT,
    canceled TINYINT DEFAULT 0,
    canceled_message VARCHAR(64) DEFAULT '',

    plan_id INT NOT NULL,
    customer_id INT NOT NULL,
    user_id INT NOT NULL,
    server_id INT NOT NULL,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    CONSTRAINT pk_contracts PRIMARY KEY (contract_id),
    CONSTRAINT fk_contracts_plans FOREIGN KEY (plan_id) REFERENCES plans (plan_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_contracts_customers FOREIGN KEY (customer_id) REFERENCES customers (customer_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_contracts_users FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments(
    payment_id INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) DEFAULT '',
    datetime_of_issue DATETIME NOT NULL,
    reference VARCHAR(32) DEFAULT '',
    total_taxed FLOAT(8,2) DEFAULT 0.00,
    total_igv FLOAT(8,2) DEFAULT 0.00,
    total FLOAT(8,2) NOT NULL,
    from_datetime  DATE NOT NULL,
    to_datetime  DATE NOT NULL,
    payment_count INT NOT NULL,
    canceled TINYINT DEFAULT 0,
    canceled_message VARCHAR(64) DEFAULT '',

    contract_id INT NOT NULL,
    user_id INT NOT NULL,

    updated_at DATETIME,
    created_at DATETIME,
    created_user_id INT,
    updated_user_id INT,
    CONSTRAINT pk_payments PRIMARY KEY (payment_id),
    CONSTRAINT fk_payments_contracts FOREIGN KEY (contract_id) REFERENCES contracts (contract_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_payments_users FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;


INSERT INTO companies(document_number, social_reason, representative) VALUES ('99999999999','example','example');

INSERT INTO identity_document_types(code, description) VALUES
('1', 'DNI'),
('4', 'CARNET DE EXTRANJERIA'),
('6', 'RUC'),
('7', 'PASAPORTE');


INSERT INTO app_authorizations (module,action,description,state)
VALUES ('company','listar','listar roles',true),
       ('company','modificar','Actualizar empresa',true),

       ('server','listar','listar servidores',true),
       ('server','crear','crear nuevos servidor',true),
       ('server','eliminar','Eliminar un servidor',true),
       ('server','modificar','Acualizar los servidores',true),

       ('plan','listar','listar planes',true),
       ('plan','crear','crear nuevos plan',true),
       ('plan','eliminar','Eliminar un plan',true),
       ('plan','modificar','Acualizar los planes',true),

       ('customer','listar','listar clientes',true),
       ('customer','crear','crear nuevos cliente',true),
       ('customer','eliminar','Eliminar un cliente',true),
       ('customer','modificar','Acualizar los clientes',true),

       ('payment','listar','listar pagos',true),
       ('payment','crear','crear nuevos pagos',true),
       ('payment','anular','Anular un pago',true),

       ('contract','listar','listar roles',true),
       ('contract','crear','crear nuevos rol',true),
       ('contract','anular','Eliminar un rol',true);