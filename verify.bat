@echo off  
set classpath=.;%JAVA_HOME%\lib\dt.jar;%JAVA_HOME%\lib\tools.jar;E:\data\php\phpstudy\tdpay_swt\javalib\SADK-3.1.1.3.jar;
java com.tangdi.bat.CfcaVerify %1 %2