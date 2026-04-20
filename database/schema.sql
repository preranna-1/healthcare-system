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


CREATE DATABASE IF NOT EXISTS hospital_db;
USE hospital_db;

-- ================= PROFILES =================
CREATE TABLE PROFILE (
    ProfileID INT PRIMARY KEY,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Street VARCHAR(100),
    City VARCHAR(50),
    PostalCode VARCHAR(20),
    Phone VARCHAR(20),
    Email VARCHAR(100),
    PasswordHash VARCHAR(255),
    Role VARCHAR(20)
) ENGINE=InnoDB;

-- ================= PATIENT =================
CREATE TABLE PATIENT (
    PatientID INT PRIMARY KEY,
    UserID INT,
    DOB DATE,
    Gender VARCHAR(10),
    BloodGroup VARCHAR(5),
    DiseaseType VARCHAR(50),
    FOREIGN KEY (UserID) REFERENCES PROFILE(ProfileID)
) ENGINE=InnoDB;

-- ================= EMERGENCY CONTACT =================
CREATE TABLE EMERGENCY_CONTACT (
    ContactID INT PRIMARY KEY,
    UserID INT,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Phone VARCHAR(20),
    FOREIGN KEY (UserID) REFERENCES PROFILE(ProfileID)
) ENGINE=InnoDB;

-- ================= DATA MANAGER =================
CREATE TABLE DATA_MANAGER (
    ManagerID INT PRIMARY KEY,
    UserID INT,
    CenterID INT,
    FOREIGN KEY (UserID) REFERENCES PROFILE(ProfileID)
) ENGINE=InnoDB;

-- ================= PATIENT CONTACT LINK (M:N) =================
CREATE TABLE PATIENT_CONTACT_LINK (
    PatientID INT,
    ContactID INT,
    RelationToPatient VARCHAR(50),
    PRIMARY KEY (PatientID, ContactID),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (ContactID) REFERENCES EMERGENCY_CONTACT(ContactID)
) ENGINE=InnoDB;

-- ================= PATIENT RECORD =================
CREATE TABLE PATIENT_RECORD (
    RecordID INT PRIMARY KEY,
    PatientID INT,
    DiagnosisNotes TEXT,
    TreatmentDetails TEXT,
    RecordDate DATE,
    DoctorID INT,
    CenterID INT,
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID)
) ENGINE=InnoDB;

-- ================= ADMISSION HISTORY =================
CREATE TABLE ADMISSION_HISTORY (
    RecordID INT PRIMARY KEY,
    PatientID INT,
    AdmissionDate DATE,
    DischargeDate DATE,
    Outcome VARCHAR(50),
    CenterID INT,
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID)
) ENGINE=InnoDB;


--maymuna's code

-- Create Bed table
CREATE TABLE Bed (
    BedID INT PRIMARY KEY,
    CenterID INT,
    BedType VARCHAR(50),
    BedName VARCHAR(50),
    IsAvailable BOOLEAN,
    LastUpdate DATETIME);

CREATE TABLE Feedback (
    FeedbackID INT PRIMARY KEY,\
    PatientID INT,
    CenterID INT,
    DoctorID INT,
    HospitalRating INT,
    DoctorRating INT,
    EquipmentRating INT,
    SubmittedAt DATETIME,
    Comments TEXT,
    FOREIGN KEY (CenterID) REFERENCES Healthcare_Center(CenterID),
    FOREIGN KEY (DoctorID) REFERENCES doctor(DoctorID),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID)

    
);

CREATE TABLE Alert (
    AlertID INT PRIMARY KEY,
    CenterID INT,
    AlertType VARCHAR(50),
    Severity VARCHAR(20),
    Message TEXT,
    CreatedAt DATETIME,
    ResolvedAt DATETIME,
    FOREIGN KEY (CenterID) REFERENCES Healthcare_Center(CenterID)
);


CREATE TABLE Booking (
    BookingID INT PRIMARY KEY,
    PatientID INT,
    CenterID INT,
    DoctorID INT,
    BookingStatus VARCHAR(50),
    BookingDate DATETIME,
    IsEmergency BOOLEAN,
    BookingType VARCHAR(50),

    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID),
    FOREIGN KEY (CenterID) REFERENCES HealthCenter(CenterID),
    FOREIGN KEY (DoctorID) REFERENCES Doctor(DoctorID)
);
