<?php

namespace App\Helper;

class Permission
{
	
	//Administration
	public $MANAGE_ADMINSTRATOR = 'Administration';
	public $MANAGE_DASHBOARD = 'Dashboard';
	public $MANAGE_USER = 'Users';
	public $MANAGE_PASSWORD = 'Change Password';
	public $MANAGE_USER_TYPE = 'user type';
	public $MANAGE_USER_COMPANY = 'user company';
	public $MANAGE_USER_LOCATION = 'user location';
	public $MANAGE_ROLE = 'Roles';
	public $MANAGE_PERMISSION = 'permission';
	public $MANAGE_CONFIGS	  = 'Configurations';
	
	// Setup
	public $MANAGE_SETUP = 'Setup';
	public $MANAGE_BANK = 'Banks';
    public $MANAGE_TENANT = 'Tenant';
	public $MANAGE_COMPANY = 'Company';
	public $MANAGE_COUNTRY = 'Country';
	public $MANAGE_PROJECT = 'Project';
	public $MANAGE_LOCATION = 'Location';
	public $MANAGE_CURRENCY = 'Currency';
	public $MANAGE_CURRENCY_EXCHANGE = 'currency exchange';
	public $MANAGE_SAL_TAX_SLAB = 'Salary Tax Slab';
	public $MANAGE_FINANCIAL_YEAR = 'Financial Year';
	public $MANAGE_ATT_DEPT = 'Department';
	public $MANAGE_ATT_DESG = 'Designation';
	public $MANAGE_ATT_ETYPE = 'Etype';
	public $MANAGE_ATT_SHIFT = 'Shift';
	public $MANAGE_ATT_ROSTER_SHIFT = 'Shift Roster';
	public $MANAGE_ATT_GRADE = 'Grade';
	public $MANAGE_ATT_LEAVE = 'Leave Balance';
	public $MANAGE_ATT_GENDER = 'Gender';
	public $MANAGE_ATT_WEEKDAY = 'Weekday';
	public $MANAGE_ATT_HOLIDAY = 'Holiday';
	public $MANAGE_ATT_ATTCODE = 'Attcode';
	public $MANAGE_ATT_RAMAZAN = 'Ramazan';
	public $MANAGE_ATT_ATTGROUP = 'Attgroup';
	public $MANAGE_ATT_ALLOWDED = 'Allowance/Deduction';
	public $MANAGE_ATT_RELIGION = 'Religion';
	public $MANAGE_ATT_DEPT_GROUP = 'Department Group';
	public $MANAGE_ATT_JOB_STATUS = 'Job Status';
	public $MANAGE_ATT_LEFT_STATUS = 'Left Status';
	public $MANAGE_ATT_ALLOWDED_GROUP = 'Allowded group';
	public $MANAGE_ATT_ENTITLEMENT_TYPE = 'Entitlement Type';
	public $MANAGE_ATT_PROBATION_STATUS = 'Probation Status';
	public $MANAGE_ATT_CLOSING_MONTH  = 'Closing Month';
	public $MANAGE_SETUP_REPORTS 	= 'Setup Reports';
	public $MANAGE_SALE_TYPES = 'Sale Types';
	public $MANAGE_LOAN_TYPES = 'Loan Types';

	//Employee
	public $MANAGE_EMP	   = 'Employee';
	public $MANAGE_EMP_CODE_LIST = 'Employee Code List';
	public $MANAGE_EMP_INFO	   = 'Employee Main';
	public $MANAGE_EMP_TRIAL = 'Trial Employee';
	public $MANAGE_EMP_TRIAL_CARD_PRINTING = 'Trial Card Printing';
	public $MANAGE_EMP_FIX_TAX = 'Employee FixTax';
	public $MANAGE_EMP_CLOSING_MONTH_CHEQUE = 'Closing Month Cheque';
	public $MANAGE_EMP_LOC_TRANSFER = 'Employee Transfer';
	public $MANAGE_EMP_LOCAL_SALE = 'Local Sale';
	public $MANAGE_EMP_Loan = 'Loan';
	public $MANAGE_LOAN_DEDUCTION = 'Loan Deduction';
	public $MANAGE_EMP_Advance = 'Advance';
	public $MANAGE_EMP_CARD_PRINTING = 'Card Printing';
	public $MANAGE_EMP_REPORT = 'Employee Report';

	//Time Entry
	public $MANAGE_TIME_ENTRY  = 'Time Entry';
	public $MANAGE_TE_Att_ENTRY  = 'Attendance Entry';
	public $MANAGE_TE_CHANGE_Att_EMPLOYEE  = 'Change Attendance Employee';
	public $MANAGE_EMP_MONTH_ATT_DAYS = 'Month Attendance Days';
	public $MANAGE_TE_DAILY_MANUAL_OT = 'O/T Entry - Daily';
	public $MANAGE_TE_MONTHLY_OT_ENTRY = 'O/T Entry - Monthly';
	public $MANAGE_TE_OFFICIAL_VISIT_ENTRY = 'Official Visit Entry';
	public $MANAGE_TE_LEAVE_ENTRY = 'Leave Entry';
	public $MANAGE_TE_LEAVE_APPROVAL = 'Leave Approval';
	public $MANAGE_TE_ROSTER_ENTRY = 'Roster Entry';

	//Posting
	public $MANAGE_POSTING = 'Posting';
	public $MANAGE_Att_POSTING = 'Attendance Posting';
	public $MANAGE_SALARY_POSTING = 'Salary Posting';
	
	//Reports
	public $MANAGE_REPORTS = 'Reports';
	public $MANAGE_Att_LISTING_REPORT = 'Daily Attendance';
	public $MANAGE_Att_MONTHLY_REPORT = 'Monthly Attendance';
	public $MANAGE_MONTHLY_SALARY_REPORT = 'Monthly Salary';
}