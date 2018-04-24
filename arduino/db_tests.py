import unittest
import db_interaction

class TestDBInteractions(unittest.TestCase):

    def test_connect_db(self):
        # Connects to mysql db.
        self.assertTrue(db_interaction.connect())

    def test_close_connections(self):
        # Obtains connection and cursor objects and ensures they are properly closed.
        conn = db_interaction.connect()
        cursor = conn.cursor()
        self.assertTrue(db_interaction.close_connections(cursor, conn))

    def test_execute_valid_insert_query(self):
        # Sends number of people to mysql db and returns the number inserted to db.
        sql = (
          "INSERT INTO Counter (num_people) "
          "VALUES (%s)"
        ) 
        # Comma needed to make it a tuple.
        data = (1,)
        self.assertTrue(db_interaction.execute_insert_query(sql, data))

    def test_execute_failing_insert_query(self):
        # Sends invalid query, and should return False.
        sql = (
          "INSERT INTO invalid_table_name (num_people) "
          "VALUES (%d)"
        ) 
        data = (1,)
        self.assertFalse(db_interaction.execute_insert_query(sql, data))

    def test_execute_valid_select_query(self):
        # Gets first id in db.
        sql = "SELECT MIN(id) FROM Counter WHERE num_people = %(num_people)s"
        data = {"num_people": 1}
        self.assertEqual(db_interaction.execute_insert_query(sql, data), 1)

    def test_execute_failing_select_query(self):
        # Sends invalid query, and should return False.
        sql = "SELECT MIN(id) FROM Counter WHERE INVALID_COL_NAME = %(num_people)s"
        data = {"num_people": 1}
        self.assertFalse(db_interaction.execute_insert_query(sql, data))

    def test_get_last_row_id(self):
        # Test getting the last row id. 
        result1 = db_interaction.get_last_row_id()

        # Insert one person.
        sql = (
          "INSERT INTO Counter (num_people) "
          "VALUES (%s)"
        ) 
        data = (1,)
        db_interaction.execute_insert_query(sql, data)

        result2 = db_interaction.get_last_row_id()
        self.assertNotEqual(result1, result2) # Make sure they are not the same becuase we have inserted one since we last checked.


    
if __name__ == '__main__':
    unittest.main()