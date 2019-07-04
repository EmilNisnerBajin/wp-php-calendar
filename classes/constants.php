<?php

	// Constants for User
	define("TBL_USER", "User");
	define("COL_USER_ID", "id");
	define("COL_USER_USERNAME","username");
	define("COL_USER_PASSWORD","password");
	define("COL_USER_PASSWORD2","password2");
	define("COL_USER_FIRSTNAME","firstName");
	define("COL_USER_LASTNAME","lastName");
	define("COL_USER_ADDRESS","address");
	define("COL_USER_CITY","city");
	define("COL_USER_POSTCODE","postcode");
	define("COL_USER_COUNTRY","country");
	define("COL_USER_BIRTHDAY","birthday");
	define("COL_USER_GENDER","gender");
	define("COL_USER_EMAIL","email");
	define("COL_USER_WEIGHT","weight");
	define("COL_USER_HEIGHT","height");
	define("COL_USER_IMAGE","image");
	
	// Constants for Task
	define("TBL_TASK","Task");
	define("COL_TASK_ID","id");
	define("COL_TASK_USERID","userId");
	define("COL_TASK_NAME","name");
	define("COL_TASK_STARTDATE","startDate");
	define("COL_TASK_ENDDATE","endDate");
	define("COL_TASK_IMPORTANCE","importance");
	define("COL_TASK_REPEAT","repetition");
	define("COL_TASK_TIME","time");

	//Constants for file
	define("DATABASE_INI_FILE","config.ini");

	//Constants for Income
	define("TBL_INCOME","Income");
	define("COL_INCOME_ID","idIncome");
	define("COL_INCOME_DATE","dateOfIncome");
	define("COL_INCOME_SOURCE","source");
	define("COL_INCOME_AMOUNT","amount");


	//Constants for Expense table
	define("TBL_EXP","Expense");
	define("COL_EXP_ID","idExpense");
	define("COL_EXP_DATE","dateOfExpense");
	define("COL_EXP_ESS","essential");
	define("COL_EXP_ITEM","item");
	define("COL_EXP_AMOUNT","amount");

	//Za sesiju datuma, prikazujem najvise 3 poslednje pregledana
	define("MAX_DATE", 3);

?>