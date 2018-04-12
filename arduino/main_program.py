from pymata_aio.pymata3 import PyMata3
from pymata_aio.constants import Constants
from mutex_lock import MutexLock
import time
import threading
import datetime
import db_interaction

ARDUINO_WAIT = 2 # amount of time to allow for an Arduino reset
SONAR_2_ECHO_PIN = 10 
SONAR_1_ECHO_PIN = 12
# Flag that is set when the Left sensor is hit, and reset either after 1.3 seconds in left_flag_set_wait, or when the Right sensor is hit.
LEFT_READ_FLAG = False 
board = None # board object representing Arduino.
lock = MutexLock() # Mutex lock for altering LEFT_READ_FLAG.


def instantiate_board():
    """
    Function to instantiate the object representing the Arduino board.
    Inputs: None.
    Outputs: True with the board object created it successful, False with None otherwise. 
    """

    global board
    board = PyMata3(ARDUINO_WAIT)
    if board is not None:
        board.sleep(1)
        return True, board
    else:
        # Error somewhere
        return False, board


def instantiate_sonar_sensors(board):
    """
    Function to instantiate 2 sonar sensors.
    Input: board - object representing the Arduino board.
    Output: True if successfull, False otherwise.
    """

    try:
        board.sonar_config(SONAR_1_ECHO_PIN, SONAR_1_ECHO_PIN, left_sonar_callback)
        board.sonar_config(SONAR_2_ECHO_PIN, SONAR_2_ECHO_PIN, right_sonar_callback)
        return True
    except:
        return False


def left_flag_set_wait():
    """
    Funtion that turns the LEFT_READ_FLAG to false if the right sensor does not get
    hit for 1.3 seconds. This is the case when someone walks by in the direction we are not 
    recording.
    Inputs: None.
    Output: True if successful, False otherwise.
    """

    # Get global variables.
    global lock
    global LEFT_READ_FLAG

    time.sleep(1.3)

    lock.get_lock() # Acquire lock to alter LEFT_READ_FLAG.
    try:
        if LEFT_READ_FLAG:
            LEFT_READ_FLAG = False
            print("Flag closed")
    except:
        lock.release_lock()
        return False
    finally:
        lock.release_lock()
        return True



def left_sonar_callback(data):
    """
    Ping callback funtion that is ran whenever the left sonar sensor's data is changed.
    Inputs: data - contains # of cm away that object is.
    Output: None. 
    """

    # Get global variables.
    global LEFT_READ_FLAG
    global board
    global lock

    # Acquire lock to alter LEFT_READ_FLAG.
    lock.get_lock()
    try:
        if not LEFT_READ_FLAG and data[1] < 100:
            LEFT_READ_FLAG = True  # Set flag.
            threading.Thread(target=left_flag_set_wait).start() # Start thread for LEFT_READ_FLAG.
            print("left flag set")
            # print('Left sensor: ' + str(data[1]) + ' centimeters')
    finally:
        lock.release_lock()
        return True

def right_sonar_callback(data):
    """
    Ping callback funtion that is ran whenever the left sonar sensor's data is changed.
    Inputs: data - contains # of cm away that object is.
    Output: None. 
    """

    # Get global variables.
    global LEFT_READ_FLAG
    global board
    global lock

    # Acquire lock to alter LEFT_READ_FLAG.
    lock.get_lock()
    try:
        if LEFT_READ_FLAG and data[1] < 100:
            LEFT_READ_FLAG = False  # Reset flag.
            print("Person walked by")
            count_person(1)
            # print('Right sensor: ' + str(data[1]) + ' centimeters')
    finally:
        lock.release_lock()    
        return True


def read_pin(board, pin):
    """
    Function to read the data in the pin specified.
    Inputs: board - object representing the Arduino board, pin - pin number to be read.
    Outputs: Value of pin on Arduino board.
    """

    try:
        value = board.digital_read(pin)
        print('Pin: ' + str(pin) + ' Value: ' + str(value))
        return value
    except:
        return False



def write_pin(board, pin, value):
    """
    Function to write the value in the pin specified.
    Inputs: board - object representing the Arduino board, pin - pin number to be writted to, value - value to write to pin.
    Outputs: True if successful, False otherwise.
    """

    try:
        board.digital_pin_write(pin, value)
        return True
    except:
        return False


def count_person(num_people):
    """
    Sends  1 to mysql db and returns the number inserted to db, as long as double counting is not occuring.
    Input: num_people - number of people to be inserted into db (so it can be extended later).
    Output: returns number of people inserted if successful, False otherwise.
    """
    if is_double_counting():
        return False
    else:
        sql = (
          "INSERT INTO Counter (num_people) "
          "VALUES (%s)"
        ) 
        data = (num_people,)  # Comma needed to make it a tuple.
        success = db_interaction.execute_insert_query(sql, data)
        if success:
            print("Counted person.")
            return num_people
        else:
            return False


def is_double_counting():
    """
    Checks to see if a person is being double counted by making sure it has been 1.5 seconds since the 
    last time a person was recorded in the db.
    Input: none.
    Output: True if a person is being double counted, False otherwise.
    """
    sql = "SELECT timestamp FROM Counter WHERE id = %(id)s"
    data = {"id": db_interaction.get_last_row_id()}
    result = db_interaction.execute_select_query(sql, data)

    if result is not None:
        now = datetime.datetime.now()
        diff = now-result # Difference between last update and current time.
        if diff.seconds < 1: # If less than 1.3 seconds, double counting has 
            return True
    return False



def main():
    try:
        global LEFT_READ_FLAG # Make sure it is global.

        connected, board = instantiate_board()
        LEFT_READ_FLAG = False # Initialize flag.

        if connected:
            instantiate_sonar_sensors(board)

            while True:
                # Program is continusously run and the left/right_sonar_callback funtions are 
                # run whenever the respective sensor's value changes.
                board.sleep(.1)
    finally:
        print("Program ending.")


if __name__ == "__main__":
    main()
