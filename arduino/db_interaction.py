import mysql.connector


host_name = "database.cse.tamu.edu";
db_name = "XXXXXX";
user = "XXXXXX";
password = "XXXXXX";

def connect():
    """
    Function to connect to MySQL database.
    Inputs: None.
    Outputs: Connection object to MySQL datbase if successful, False otherwise.
    """
    try:
        conn = mysql.connector.connect(user=user, password=password, host=host_name, database=db_name)
        return conn

    except mysql.connector.Error as err:
        # Various types of errors.
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("Something is wrong with your user name or password")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print("Database does not exist")
        else:
            print(err)
        return None


def close_connections(cursor, conn):
    """
    Function to close connection to MySQL database.
    Inputs: None.
    Outputs: True if successful, False otherwise.
    """
    try:
        cursor.close()
        conn.close()
        return True
    except:
        return False


def execute_insert_query(sql, data):
    """
    Function to connect to MySQL database, and execute inputted query.
    Inputs: sql - MySQL command to execute, data - data that goes along with sql command.
    Outputs: True if successful, False otherwise.
    """
    try:
        conn = connect()
        if conn is not None:
            cursor = conn.cursor(buffered=True)

            cursor.execute(sql, data)
            conn.commit()

            close_connections(cursor, conn)

            return True
        else:
            return False
    except:
        return False


def execute_select_query(sql, data):
    """
    Function to connect to MySQL database, and execute inputted query.
    Inputs: sql - MySQL command to execute, data - data that goes along with sql command.
    Outputs: The result of the select query if successful, False otherwise.
    """
    try:
        conn = connect()
        if conn is not None:
            cursor = conn.cursor(buffered=True)
            
            cursor.execute(sql, data)
            conn.commit()
            result = cursor.fetchone()

            close_connections(cursor, conn)

            return result[0]
        else:
            return False
    except:
        return False    


def get_last_row_id():
    """
    Connects to MySQL database, and gets the max id to find the last created row.
    Input: none.
    Output: Max id if successful, False otherwise.
    """
    try:
        conn = connect()
        if conn is not None:
            cursor = conn.cursor(buffered=True)
            sql = "SELECT MAX(id) AS id FROM Counter"
            cursor.execute(sql)
            conn.commit()
            result = cursor.fetchone()
            close_connections(cursor, conn)
            return result[0]
    except:
        return False