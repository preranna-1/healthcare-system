-- Prerana's code

-- Create DISEASE_CATEGORY table
CREATE TABLE DISEASE_CATEGORY (
    DCID VARCHAR(50) PRIMARY KEY,
    NAME VARCHAR(100),
    DiseaseCategory VARCHAR(100)
);

-- Create HEALTHCARE_CENTER table
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

-- Create CENTERDISEASE_CATEGORY table
CREATE TABLE CENTERDISEASE_CATEGORY (
    CenterID VARCHAR(50),
    DCID VARCHAR(50),
    PRIMARY KEY (CenterID, DCID),
    CONSTRAINT FK_CDC_Center FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID),
    CONSTRAINT FK_CDC_Disease FOREIGN KEY (DCID) REFERENCES DISEASE_CATEGORY(DCID)
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
    CenterID VARCHAR(50),
    RegistrationNumber VARCHAR(50) PRIMARY KEY,
    DriverName VARCHAR(100),
    DriverPhone VARCHAR(50),
    Type VARCHAR(50), -- Basic, ICU, Cardiac
    IsAvailable BOOLEAN,
   
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

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
    CenterID VARCHAR(50),
    FOREIGN KEY (UserID) REFERENCES PROFILE(ProfileID),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
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
    DoctorID VARCHAR(50),
    CenterID VARCHAR(50),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (DoctorID) REFERENCES DOCTOR(DoctorID),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
) ENGINE=InnoDB;

-- ================= ADMISSION HISTORY =================
CREATE TABLE ADMISSION_HISTORY (
    RecordID INT PRIMARY KEY,
    PatientID INT,
    AdmissionDate DATE,
    DischargeDate DATE,
    Outcome VARCHAR(50),
    CenterID VARCHAR(50),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
) ENGINE=InnoDB;


-- Maymuna's code

-- Create Bed table
CREATE TABLE Bed (
    BedID INT PRIMARY KEY,
    CenterID VARCHAR(50),
    BedType VARCHAR(50),
    BedName VARCHAR(50),
    IsAvailable BOOLEAN,
    LastUpdate DATETIME,
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

-- Create Alert table
CREATE TABLE Alert (
    AlertID INT PRIMARY KEY,
    CenterID VARCHAR(50),
    AlertType VARCHAR(50),
    Severity VARCHAR(20),
    Message TEXT,
    CreatedAt DATETIME,
    ResolvedAt DATETIME,
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID)
);

-- Create Booking table
CREATE TABLE Booking (
    BookingID INT PRIMARY KEY,
    PatientID INT,
    CenterID VARCHAR(50),
    DoctorID VARCHAR(50),
    BookingStatus VARCHAR(50),
    BookingDate DATETIME,
    IsEmergency BOOLEAN,
    BookingType VARCHAR(50),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID),
    FOREIGN KEY (DoctorID) REFERENCES DOCTOR(DoctorID)
);

-- Create Appointment table
CREATE TABLE Appointment (
    BookingID INT PRIMARY KEY,
    AppointmentDate DATE,
    TimeSlot VARCHAR(50),
    FOREIGN KEY (BookingID) REFERENCES Booking(BookingID)
);

-- Create Admission table
CREATE TABLE Admission (
    BookingID INT PRIMARY KEY,
    BedID INT,
    AdmissionDate DATE,
    ExpectedStay INT,
    FOREIGN KEY (BookingID) REFERENCES Booking(BookingID),
    FOREIGN KEY (BedID) REFERENCES Bed(BedID)
);

-- Create Ambulance_Request table
CREATE TABLE Ambulance_Request (
    RequestID INT PRIMARY KEY,
    PatientID INT,
    RegistrationNumber VARCHAR(50),
    PickupLocation VARCHAR(255),
    RequestTime DATETIME,
    ArrivedAt DATETIME,
    Status VARCHAR(50),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (RegistrationNumber VARCHAR(50)) REFERENCES AMBULANCE(RegistrationNumber VARCHAR(50))
);

-- Create Feedback table
CREATE TABLE Feedback (
    FeedbackID INT PRIMARY KEY,
    BookingID INT,
    PatientID INT,
    CenterID VARCHAR(50),
    DoctorID VARCHAR(50),
    HospitalRating INT,
    DoctorRating INT,
    EquipmentRating INT,
    SubmittedAt DATETIME,
    Comments TEXT,
    FOREIGN KEY (BookingID) REFERENCES Booking(BookingID),
    FOREIGN KEY (PatientID) REFERENCES PATIENT(PatientID),
    FOREIGN KEY (CenterID) REFERENCES HEALTHCARE_CENTER(CenterID),
    FOREIGN KEY (DoctorID) REFERENCES DOCTOR(DoctorID)
);
