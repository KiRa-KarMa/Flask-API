import pandas as pd
import smtplib
from datetime import datetime
from EffortAndGratitude.Controllers.func_herr_effort import *
# import MySQLdb
from string import Template
import os


def EnviarEmail_effort():
    """
    Envia un correo electronico a los usuarios pertinentes alamacenados en la base de datos
    """

    try:
        section = "ADVANCE"
        engine_sql = connectDB(os.path.join('.', 'DB_cnf_effort_code.ini'), section)

        df_sql = pd.read_sql('SELECT * FROM V_CORREOS_TRABAJADORES WHERE NIF IS NOT NULL', con=engine_sql)
        print(df_sql.loc[:, 'NIF':'Direccion'].sort_values(by='NIF'))

        engine_mysql = connectDB(os.path.join('.', 'DB_cnf_effort_code.ini'), 'EXCEL')

        df_mysql_total = pd.read_sql('SELECT * FROM vw_suma_puntos', con=engine_mysql)
        df_mysql_total = df_mysql_total.sort_values(by='NIF')
        print(df_mysql_total.iloc[:])

        df_mysql_mes = pd.read_sql('SELECT * FROM vw_puntos_mes', con=engine_mysql)
        df_mysql_mes = df_mysql_mes.sort_values(by='NIF')
        print(df_mysql_mes.iloc[:])

        #TODO: En este dataframe faltan registros porque no se pueden unir los usuarios que no tienen el dni en la tabla
        df_merged = df_sql.merge(df_mysql_total, how='inner', on= 'NIF').sort_values(by='NIF')
        df_merged = df_merged.merge(df_mysql_mes, how='inner', on= 'NIF').sort_values(by='NIF')
        df_merged_outer = df_sql.merge(df_mysql_total, how='outer', on='NIF').sort_values(by='NIF')
        df_merged_outer = df_merged_outer.merge(df_mysql_mes, how='outer', on='NIF').sort_values(by='NIF')
        print("**********************************")
        #df_merged_outer.to_csv('C:/Users/usuario/Documents.df_test.csv')
        for col in df_merged.columns:
            print(col)

        df_concat = pd.concat((df_merged['DIRIGIDO A_x'], df_merged['EMail'], df_merged['PUNTOS_MES'], df_merged['PUNTOS_TOTAL'], df_merged['Direccion']), axis = 1).sort_values(by = ["DIRIGIDO A_x"])
        print("---------------------------------------------------")
        print(df_concat.iloc[:])

        email_responsables = df_concat['Direccion'].unique().tolist()

        print(email_responsables)

        '''
        # Calculo de puntos por departamento
        try:
            df_dpto = pd.read_sql('SELECT * FROM V_CORREOS_RESPONSABLES ORDER BY id_dpto ASC', con=engine_sql).sort_values(by='id_dpto')
        except Exception as e:
            print (e)

        #print(df_dpto)

        cursor_sql = connectDB_sqlServer('C:/Git/dev/ExcelToDB/venv/DB_cnf.ini', section)
        db = connectDB_mysql('C:/Git/dev/ExcelToDB/venv/DB_cnf.ini')
        cursor = db.cursor()
        puntos_dptos = []
        for i in range (0, len(df_dpto)):
            try:
                df_users = pd.read_sql("SELECT NIF FROM V_CORREOS_TRABAJADORES WHERE CentroCosteId =" + str(df_dpto.iloc[i, 0]), con=engine_sql)
            except Exception as e:
                print("Error en V_CORREOS_TRABAJADORES: " + e)
            puntos_mes = 0
            puntos = 0
            for j in range(0, len(df_users)):
                try:
                    cursor.execute("SELECT PUNTOS_MES FROM funfriends.vw_puntos_mes WHERE NIF = '" + str(df_users.iloc[j,0]) + "'") # , (df_users.iloc[j,0])
                    data = cursor.fetchall()
                    if (len(data) > 0):
                        puntos_mes += int(data[0][0])
                    cursor.execute("SELECT PUNTOS_TOTAL FROM funfriends.vw_suma_puntos WHERE NIF = '" + str(df_users.iloc[j, 0]) + "'") # , (df_users.iloc[j,0])
                    data = cursor.fetchall()
                    if (len(data) > 0):
                        puntos += int(data[0][0])
                except Exception as e:
                    print ("Error en vw_puntos_mes: " + e)
            puntos_dptos.append([df_dpto.iloc[i,0], puntos_mes, puntos])

        df_puntos_dpto = pd.DataFrame(puntos_dptos, columns=['id_dpto','puntos_mes','puntos'])
        # print (df_puntos_dpto)

        df_merged_dpto = df_dpto.merge(df_puntos_dpto, how='inner', on='id_dpto').sort_values(by='id_dpto')
        print (df_merged_dpto)
        '''
        # ********************************************************
        # ********************************************************
        #               Configuracion envío correos
        # ********************************************************
        # ********************************************************
        fromaddr = "comunicaciones_internas@nutandme.com"
        password = "727F({/CHcU4" #
        server = smtplib.SMTP('nutandme-com.correoseguro.dinaserver.com', 587)
        server.starttls()
        server.login(fromaddr, password)
        subject = "Effort&Gratitude's program"

        # Puntos individuales.
        for i in range (0, len(df_concat)):
            try:
                nombre = df_concat.iloc[i,0]
                email = df_concat.iloc[i,1]
                puntos_mes = df_concat.iloc[i,2]
                puntos_total = df_concat.iloc[i,3]
                last_month = datetime.now().month - 1
                year = datetime.now().year

                if (datetime.now().month == 1):
                    last_month = 12
                    year = datetime.now().year - 1
                motivo = pd.read_sql("SELECT * FROM motivos where `dirigido a` = '" + str(nombre) + "' and MONTH(`marca temporal`) = " + str(last_month) + " and YEAR(`marca temporal`) = " + str(year), con=engine_mysql)
                print("----------------------------------")
                print("SELECT * FROM motivos where `dirigido a` = '" + str(nombre) + "' and MONTH(`marca temporal`) = " + str(
                    last_month) + " and YEAR(`marca temporal`) = " + str(year))
                str_motivo = ''
                for i in range(0, len(motivo)):
                    str_motivo = str(str_motivo) + '- "' +str(motivo.iloc[i,1]) + '"' + '<br/>'
                print(str_motivo)

                toaddr = str(email)
                #toaddr = 'analyst@calconut.es'
                #toaddr = 'production3@calconut.es'
                if int(puntos_mes) == 1:
                    if int(puntos_total) == 1:
                        html = """\
                        <html>
                          <head></head>
                          <body>
                            <br><br>
                            <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes has conseguido
                                            <u>$puntos_mes punto</u> y en total acumulas $puntos_total punto!!</p>

                            <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                            <p> Hay alguien que quiere agradecerte tu forma de trabajar, así que sigue así,
                                creciendo cada día para ser mejor trabajador/a y mejor compañero/a.</p>
                            <hr>
                            <p style="color: black; font-weight: bold;">MOTIVOS DE LA VOTACIÓN:<p/>
                            <p><i>$motivo<i/><p/>
                            <hr>
                            <p style="color: red; font-weight: bold;">**RECUERDA: Los puntos son acumulables y para canjearlos debes contactar con Recursos Humanos.</p>
                          </body>
                        </html>
                        """
                    elif int(puntos_total) > 1:
                        html = """\
                        <html>
                          <head></head>
                          <body>
                            <br><br>
                            <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes has conseguido
                                            <u>$puntos_mes punto</u> y en total acumulas $puntos_total puntos!!</p>

                            <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                            <p> Hay alguien que quiere agradecerte tu forma de trabajar, así que sigue así,
                                creciendo cada día para ser mejor trabajador/a y mejor compañero/a.</p>
                            <hr>
                            <p style="color: black; font-weight: bold;">MOTIVOS DE LA VOTACIÓN:<p/>
                            <p><i>$motivo<i/><p/>
                            <hr>
                            <p style="color: red; font-weight: bold;">**RECUERDA: Los puntos son acumulables y para canjearlos debes contactar con Recursos Humanos.</p>
                          </body>
                        </html>
                        """

                elif int(puntos_mes) > 1:
                    html = """\
                    <html>
                      <head></head>
                      <body>
                        <br><br>
                        <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes has conseguido
                                        <u>$puntos_mes puntos</u> y en total acumulas $puntos_total puntos!!</p>

                        <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                        <p> Hay alguien que quiere agradecerte tu forma de trabajar, así que sigue así,
                            creciendo cada día para ser mejor trabajador/a y mejor compañero/a.</p>
                        <hr>
                        <p style="color: black; font-weight: bold;">MOTIVOS DE LA VOTACIÓN:<p/>
                        <p><i>$motivo<i/><p/>
                        <hr>
                        <p style="color: red; font-weight: bold;">**RECUERDA: Los puntos son acumulables y para canjearlos debes contactar con Recursos Humanos.</p>
                      </body>
                    </html>
                    """

                # mensaje = mensaje.encode("utf-8")

                from email.mime.text import MIMEText
                from email.mime.multipart import MIMEMultipart
                from email.mime.base import MIMEBase

                s = Template(html).safe_substitute(puntos_mes=str(int(puntos_mes)), puntos_total=str(int(puntos_total)), motivo=str(str_motivo))
                msg = MIMEMultipart('alternative')
                msg['From'] = fromaddr
                msg['To'] = toaddr
                msg['Subject'] = subject
                # body = mensaje
                # msg.attach(MIMEText(body))
                msg.attach(MIMEText(s, 'html'))
                text = msg.as_string()
                server.sendmail(fromaddr, toaddr, text)
                print("Correo enviado a: " + str(email))

            except Exception as e:
                print("No se ha podido enviar el correo electronico a " + str(nombre) + " por el error: " + str(e))
                raise Exception("No se ha podido enviar el correo electronico a " + str(nombre), e)

        print("Comenzamos envio responsables")
        # Puntos Dpto.
        for email in email_responsables:
            try:
                '''
                df_mask = df_merged['Direccion'] == email
                posiciones = np.flatnonzero(df_mask)
                df_miembros_dpto_puntuados = df_merged.iloc[posiciones]
                print('-------------------------------------')
                print (df_miembros_dpto_puntuados)
                print ('------------------------------------')
                '''

                toaddr = email
                # toaddr = 'analyst@calconut.es'
                # toaddr = 'production3@calconut.es'
                df_mask_out = df_merged_outer['Direccion'] == email
                print(df_mask_out)
                df_miembros_dpto = df_merged_outer[df_mask_out].fillna(0)
                print('-------------------------------------')
                # print(df_miembros_dpto.loc[:, ['Nombre', 'DIRIGIDO A_x', 'PUNTOS_TOTAL', 'PUNTOS_MES']])

                body_intro = """\
                                   <html>
                                     <head></head>
                                     <body>
                                       <br><br>
                                       <p style="color: #000000; font-weight: bold;">A continuacion se muestra un desglose de los empleados de tu equipo con los correspondientes motivos y numero de votos, aquellos que no aparecen es que no han recibido ningun punto este mes:</p>
                                       <br><br>
                                   """
                body = body_intro
                for i in range(0, len(df_miembros_dpto)):
                    nombre = df_miembros_dpto.iloc[i, 0]
                    nombre_mayus = df_miembros_dpto.iloc[i, 6]
                    apellido = df_miembros_dpto.iloc[i, 1]
                    puntos_mes = df_miembros_dpto.iloc[i, 9]
                    puntos_total = df_miembros_dpto.iloc[i, 7]

                    last_month = datetime.now().month - 1
                    year = datetime.now().year
                    if (datetime.now().month == 1):
                        last_month = 12
                        year = datetime.now().year - 1
                    motivo = pd.read_sql("SELECT * FROM motivos where `dirigido a` = '" + str(nombre_mayus) +"' and MONTH(`marca temporal`) = " + str(last_month) + " and YEAR(`marca temporal`) = " + str(year),
                                         con=engine_mysql)
                    print("SELECT * FROM motivos where `dirigido a` = '" + str(nombre_mayus) + "' and MONTH(`marca temporal`) = " + str(last_month) + " and YEAR(`marca temporal`) = " + str(year))
                    str_motivo = ''
                    if (len(motivo) == 0):
                        str_motivo = 'Sin puntos en este mes.<br/>'

                    else:
                        for j in range(0, len(motivo)):
                            str_motivo = str(str_motivo) + '- "' + str(motivo.iloc[j, 1]) + '"' + '<br/>'

                        html = """\
                                    <br><br>
                                    <p style="color: #28325D; font-weight: bold;">$nombre $apellidos : </p> 
                                    <p style="color: #F1A200; font-weight: bold;">Este mes ha conseguido $puntos_mes y en total acumula $puntos_total .</p>
                                    <br><br>
                                    <p style="color: #000000; font-weight: bold;"> Motivos de los votos de este mes:</p>
                                    <p><i>$motivo<i/><p/>
                                    <hr>
                                """

                        from email.mime.text import MIMEText
                        from email.mime.multipart import MIMEMultipart
                        from email.mime.base import MIMEBase

                        s = Template(html).safe_substitute(nombre=str(nombre), apellidos=str(apellido), puntos_mes=str(int(puntos_mes)), puntos_total=str(int(puntos_total)),motivo=str_motivo)
                        body += s

                body_b = MIMEText(body, 'html')

                msg = MIMEMultipart('alternative')
                msg['From'] = fromaddr
                msg['To'] = toaddr
                msg['Subject'] = subject
                msg.attach(body_b)

                text = msg.as_string()
                server.sendmail(fromaddr, toaddr, text)
                print("Correo enviado a: " + str(email))

            except Exception as e:
                print("No se ha podido enviar el correo electronico a " + str(nombre) + ": " + e)
                raise Exception("No se ha podido enviar el correo electronico a " + str(nombre), e)

        '''
        for i in range(0, len(df_merged_dpto)):
            try:
                nombre = df_merged_dpto.iloc[i, 1]
                email = df_merged_dpto.iloc[i, 2]
                puntos_mes = df_merged_dpto.iloc[i, 3]
                puntos_total = df_merged_dpto.iloc[i, 4]

                #toaddr = "analyst@calconut.es"
                toaddr = str(email)

                if int(puntos_mes) == 1:
                    if int(puntos_total) == 1:
                        html = """\
                           <html>
                             <head></head>
                             <body>
                               <br><br>
                               <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes el departamento de $nombre ha conseguido 
                                               <u>$puntos_mes punto</u> y en total acumulais $puntos_total punto!!</p>

                               <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                               <p> Hay alguien que quiere agradecer vuestra forma de trabajar, así que seguid así, 
                                   creciendo cada día para que el resto de los compañeros valoren el esfuerzo del departamento.</p>
                               <hr>

                             </body>
                           </html>
                           """
                    elif int(puntos_total) > 1:
                        html = """\
                           <html>
                             <head></head>
                             <body>
                               <br><br>
                               <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes el departamento de $nombre ha conseguido
                                               <u>$puntos_mes punto</u> y en total acumulais $puntos_total puntos!!</p>

                               <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                               <p> Hay alguien que quiere agradecer vuestra forma de trabajar, así que seguid así,
                                   creciendo cada día para que el resto de los compañeros valoren el esfuerzo del departamento.</p>

                               <hr>

                             </body>
                           </html>
                           """


                elif int(puntos_mes) > 1:
                    html = """\
                       <html>
                         <head></head>
                         <body>
                           <br><br>
                           <p style="color: #F1A200; font-weight: bold;">¡¡WOW ENHORABUENA!! En este mes el departamento de $nombre ha conseguido 
                                           <u>$puntos_mes puntos</u> y en total acumulais $puntos_total puntos!!</p>

                           <p style="color: #28325D; font-weight: bold;">¡Todo esfuerzo tiene su recompensa!</p>

                           <p> Hay alguien que quiere agradecer vuestra forma de trabajar, así que seguid así, 
                                   creciendo cada día para que el resto de los compañeros valoren el esfuerzo del departamento.</p>

                           <hr>

                         </body>
                       </html>
                       """

                # mensaje = mensaje.encode("utf-8")
                if int(puntos_mes) >= 1:
                    from email.mime.text import MIMEText
                    from email.mime.multipart import MIMEMultipart
                    from email.mime.base import MIMEBase

                    s = Template(html).safe_substitute(puntos_mes=str(int(puntos_mes)), puntos_total=str(int(puntos_total)),
                                                       nombre=nombre)
                    msg = MIMEMultipart('alternative')
                    msg['From'] = fromaddr
                    msg['To'] = toaddr
                    msg['Subject'] = subject
                    # body = mensaje
                    # msg.attach(MIMEText(body))
                    msg.attach(MIMEText(s, 'html'))
                    text = msg.as_string()

                    server.sendmail(fromaddr, toaddr, text)
                    print("Correo enviado a: " + str(email))
            except:
                print("No se ha podido enviar el correo electronico a " + str(nombre))
                error("No se ha podido enviar el correo electronico a " + str(nombre), __name__)
        '''


        server.quit()
    except Exception as e:
        raise Exception("No se ha podido enviar el email:", e)

