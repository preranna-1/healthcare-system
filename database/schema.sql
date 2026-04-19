--Initial database file
--Prerana's code

-- Create CENTERDISEASE_CATEGORY table
CREATE TABLE CENTERDISEASE_CATEGORY (
    CenterID VARCHAR(50),
    DCID VARCHAR(50),
    PRIMARY KEY (CenterID, DCID)
);

-- Create DISEASE_CATEGORY table
CREATE TABLE DISEASE_CATEGORY (
    DCID VARCHAR(50) PRIMARY KEY,
    NAME VARCHAR(100),
    DiseaseCategory VARCHAR(100)
);

-- Create HEALTHCENTER table
CREATE TABLE HEALTHCARE_CENTER (
    CenterID VARCHAR(50) PRIMARY KEY,
    Name VARCHAR(100),
    Address VARCHAR(200),
    PhoneNumbers VARCHAR(50),
    Email VARCHAR(100),
    Latitude DECIMAL(10, 8),
    Longitude DECIMAL(11, 8),
    OperatingHours VARCHAR(100),
    OverallRating DECIMAL(3, 2),
    CenterType VARCHAR(50)
);

-- Create DIAGNOSTIC_CENTER table
CREATE TABLE DIAGNOSTIC_CENTER (
    CenterID VARCHAR(50) PRIMARY KEY,
    TestsOffered TEXT,
    ReportTurnAroundHours INT,
    EmergencyAvailable BOOLEAN,
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

-- Create REHAB_CENTER table
CREATE TABLE REHAB_CENTER (
    CenterID VARCHAR(50) PRIMARY KEY,
    TherapyTypes TEXT,
    ProgramDuration VARCHAR(100),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);


-- Create PHARMACY table
CREATE TABLE PHARMACY (
    CenterID VARCHAR(50) PRIMARY KEY,
    Is24Hours BOOLEAN,
    DeliversMedicine BOOLEAN,
    HasMedicine BOOLEAN,
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);


-- Create HOSPITAL table
CREATE TABLE HOSPITAL (
    CenterID VARCHAR(50) PRIMARY KEY,
    TotalBeds INT,
    ICUBeds INT,
    EmergencyAvailable BOOLEAN,
    SpecializationType VARCHAR(100),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

  
ALTER TABLE CENTERDISEASE_CATEGORY
ADD CONSTRAINT FK_CDC_Disease FOREIGN KEY (DCID) REFERENCES DISEASE_CATEGORY(DCID);

-- Create DOCTOR table
CREATE TABLE DOCTOR (
    DoctorID VARCHAR(50) PRIMARY KEY,
    CenterID VARCHAR(50),
    Name VARCHAR(100),
    Specialization VARCHAR(100),
    Qualification VARCHAR(200),
    YearsOfExperience INT,
    OverallRating DECIMAL(3, 2),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

-- Create AMBULANCE table
CREATE TABLE AMBULANCE (
    AmbulanceID VARCHAR(50) PRIMARY KEY,
    CenterID VARCHAR(50),
    RegistrationNumber VARCHAR(50),
    DriverName VARCHAR(100),
    DriverPhone VARCHAR(50),
    Type VARCHAR(50), -- Basic, ICU, Cardiac
    IsAvailable BOOLEAN,
    CurrentLatitude DECIMAL(10, 8),
    CurrentLongitude DECIMAL(11, 8),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);





