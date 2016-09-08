@echo off  
set classpath=.;%JAVA_HOME%\lib\dt.jar;%JAVA_HOME%\lib\tools.jar;E:\data\php\phpstudy\merchant_order_demo_swt\javalib\SADK-3.1.1.3.jar;E:\data\php\phpstudy\merchant_order_demo_swt\javalib\TdShell.jar;
java com.tangdi.bat.CfcaSig %1 %2  %3