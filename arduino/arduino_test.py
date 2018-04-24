import unittest
import main_program
import db_interaction

class TestMainProgramFunctions(unittest.TestCase):

    def test_instantiate_board(self):
        # Tests board instantiation
        success, board = main_program.instantiate_board()
        self.assertTrue(success)

    def test_instantiate_sonar_sensors_valid_board(self):
        # Tests sensor instantiation
        board_success, board = main_program.instantiate_board()
        if board_success:
            sensor_success = main_program.instantiate_sonar_sensors(board)
            self.assertTrue(sensor_success)

    def test_instantiate_sonar_sensors_invalid_board_should_fail(self):
        # Tests sensor instantiation with invalid board
        board = None
        sensor_success = main_program.instantiate_sonar_sensors(board)
        self.assertFalse(sensor_success)

    def test_left_flag_set_wait(self):
        # Tests that the flag is correctly reset if not changed outside function.
        main_program.LEFT_READ_FLAG = True 
        self.assertTrue(main_program.LEFT_READ_FLAG)
        self.assertTrue(main_program.left_flag_set_wait())
        self.assertFalse(main_program.LEFT_READ_FLAG)

    def test_read_pin(self):
        # Makes sure the pin reads what is written to it.
        success, board = main_program.instantiate_board()
        main_program.write_pin(board, 12, 1)
        self.assertEqual(main_program.read_pin(board, 12), 1)

    def test_write_pin(self):
        # Makes sure the pin writes correctly
        success, board = main_program.instantiate_board()
        main_program.write_pin(board, 12, 0)
        self.assertEqual(main_program.read_pin(board, 12), 0)

    def test_count_person(self):
        # Tests a person being inserted into db. Checks to make sure the number of people in db is increased after inserting person.
        sql = "SELECT COUNT(id) FROM Counter WHERE 1"
        data = {}
        before_add_person = db_interaction.execute_select_query(sql, data)
        result = main_program.count_person(1)
        if result == 1:
            after_add_person = db_interaction.execute_select_query(sql, data)
            self.assertNotEqual(before_add_person, after_add_person)

    def test_is_double_counting(self):
        # Tests the double counting function. 
        self.assertFalse(main_program.is_double_counting()) # Should return False because a person has not been inserted recently.
        main_program.count_person(1)
        self.assertTrue(main_program.is_double_counting()) # Should return True because a person was just inserted less than a second ago.
    

    
if __name__ == '__main__':
    unittest.main()