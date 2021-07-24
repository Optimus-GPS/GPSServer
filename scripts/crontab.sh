#!/bin/bash
#Minutos: de 0 a 59.
#Horas: de 0 a 23.
#Día del mes: de 1 a 31.
#Mes: de 1 a 12.
#Día de la semana: de 0 a 6, siendo 0 el domingo.

*/30 * * * * wget http://localhost/server/s_service.php?op=service_30min