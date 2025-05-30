-- Enable uuid-ossp extension (wajib sekali untuk UUID)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Table: admin
CREATE TABLE admin (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: events
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    start_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    picture_event VARCHAR(255),
    picture_seat VARCHAR(255),
    status BOOLEAN DEFAULT TRUE,
    created_by INT REFERENCES admin(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: ticket_types
CREATE TABLE ticket_types (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    event_id INT REFERENCES events(id) ON DELETE CASCADE,
    ticket_name VARCHAR(100) NOT NULL,
    price NUMERIC(10,2) NOT NULL,
    total_seat INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: orders
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    event_id INT REFERENCES events(id) ON DELETE CASCADE,
    ticket_type_id INT REFERENCES ticket_types(id),
    buyer_name VARCHAR(255) NOT NULL,
    buyer_email VARCHAR(255) NOT NULL,
    nik VARCHAR(20) NOT NULL,
    birth_date DATE NOT NULL,
    seat_input VARCHAR(100),
    total_payment NUMERIC(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Midtrans integration fields
    midtrans_order_id VARCHAR(100),
    transaction_status VARCHAR(50),
    payment_type VARCHAR(50),
    transaction_time TIMESTAMP,
    payment_code VARCHAR(100),
    pdf_url TEXT
);
