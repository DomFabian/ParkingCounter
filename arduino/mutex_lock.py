import threading

class MutexLock:
    """
    Mutex lock to properly alter variables with multithreaded code.
    """
    def __init__(self):
        # Initializes lock.
        self.lock = threading.Lock()

    def get_lock(self):
        # Acquires lock.
        self.lock.acquire()

    def release_lock(self):
        # Releases lock.
        self.lock.release()