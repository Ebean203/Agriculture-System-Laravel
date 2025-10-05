-- Insert test farmers
INSERT INTO farmers (farmer_id, first_name, middle_name, last_name, suffix, birth_date, gender, contact_number, barangay_id, address_details, registration_date, is_rsbsa, is_ncfrs, is_fisherfolk, is_boat) VALUES
('FRM-000001', 'Juan', 'Santos', 'Dela Cruz', '', '1980-01-01', 'Male', '09171234567', 1, 'Purok 1', '2025-09-01 10:00:00', 1, 0, 0, 0),
('FRM-000002', 'Maria', 'Reyes', 'Garcia', '', '1985-02-02', 'Female', '09181234568', 1, 'Purok 1', '2025-09-05 11:00:00', 1, 0, 0, 0),
('FRM-000003', 'Pedro', 'Cruz', 'Ramos', '', '1975-03-03', 'Male', '09191234569', 2, 'Purok 2', '2025-09-10 12:00:00', 0, 1, 0, 0),
('FRM-000004', 'Ana', 'Lopez', 'Santos', '', '1990-04-04', 'Female', '09201234570', 2, 'Purok 2', '2025-09-15 13:00:00', 0, 0, 1, 1),
('FRM-000005', 'Jose', 'Mendez', 'Torres', '', '1982-05-05', 'Male', '09211234571', 1, 'Purok 1', '2025-09-20 14:00:00', 1, 1, 0, 0),
('FRM-000006', 'Rosa', 'Flores', 'Bautista', '', '1988-06-06', 'Female', '09221234572', 2, 'Purok 2', '2025-09-25 15:00:00', 0, 1, 1, 0),
('FRM-000007', 'Carlos', 'Rivera', 'Gomez', '', '1979-07-07', 'Male', '09231234573', 1, 'Purok 1', '2025-10-01 16:00:00', 1, 0, 0, 1),
('FRM-000008', 'Elena', 'Diaz', 'Morales', '', '1992-08-08', 'Female', '09241234574', 2, 'Purok 2', '2025-10-02 17:00:00', 1, 1, 0, 0);

-- Insert test farmer commodities
INSERT INTO farmer_commodities (farmer_id, commodity_id) VALUES
('FRM-000001', 1),
('FRM-000002', 1),
('FRM-000003', 2),
('FRM-000004', 3),
('FRM-000005', 1),
('FRM-000006', 2),
('FRM-000007', 1),
('FRM-000008', 3);

-- Insert test yield monitoring data
INSERT INTO yield_monitoring (farmer_id, commodity_id, season, yield_amount, record_date, recorded_by_staff_id, unit) VALUES
('FRM-000001', 1, 'Wet Season', 150.50, '2025-09-15 10:00:00', 1, 'kg'),
('FRM-000002', 1, 'Wet Season', 200.00, '2025-09-18 11:00:00', 1, 'kg'),
('FRM-000003', 2, 'Dry Season', 180.75, '2025-09-20 12:00:00', 1, 'kg'),
('FRM-000004', 3, 'Wet Season', 120.00, '2025-09-22 13:00:00', 1, 'kg'),
('FRM-000005', 1, 'Wet Season', 175.25, '2025-09-25 14:00:00', 1, 'kg'),
('FRM-000006', 2, 'Dry Season', 190.50, '2025-09-28 15:00:00', 1, 'kg'),
('FRM-000007', 1, 'Wet Season', 210.00, '2025-10-01 16:00:00', 1, 'kg'),
('FRM-000008', 3, 'Wet Season', 165.75, '2025-10-03 17:00:00', 1, 'kg');
