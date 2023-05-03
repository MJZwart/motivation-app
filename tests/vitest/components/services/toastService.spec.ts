import {clearToast, 
    errorToast, 
    fetchStoredToasts, 
    infoToast, sendToast, 
    storeToastInLocalStorage, 
    successToast, 
    toasts} from '/js/services/toastService';


// eslint-disable-next-line max-lines-per-function
describe('Toast service', () => {
    beforeEach(() => {
        toasts.value = [];
        if (localStorage.getItem('queuedError')) localStorage.removeItem('queuedError');
    });
    it('creates a success toast with the given message', () => {
        // Arrange
        const message = 'This is a success toast';
        expect(toasts.value).toEqual([]);
        // Act
        successToast(message);
        // Assert
        expect(toasts.value).toEqual([{'success': message}]);
    });
    it('creates an error toast with the given message', () => {
        // Arrange
        const message = 'This is an error toast';
        expect(toasts.value).toEqual([]);
        // Act
        errorToast(message);
        // Assert
        expect(toasts.value).toEqual([{'error': message}]);
    });
    it('creates an info toast with the given message', () => {
        // Arrange
        const message = 'This is an info toast';
        expect(toasts.value).toEqual([]);
        // Act
        infoToast(message);
        // Assert
        expect(toasts.value).toEqual([{'info': message}]);
    });
    it('creates a custom toast with the given custom created toast', () => {
        // Arrange
        const message = 'This is a custom info toast';
        expect(toasts.value).toEqual([]);
        // Act
        sendToast({'test': message})
        // Assert
        expect(toasts.value).toEqual([{'test': message}]);
    });
    it('deletes the earliest toast given', () => {
        // Arrange
        const message1 = 'This is toast number one';
        const message2 = 'This is toast number two';
        expect(toasts.value).toEqual([]);
        // Act
        infoToast(message1);
        infoToast(message2);
        expect(toasts.value).toEqual([{'info': message1}, {'info': message2}]);
        clearToast();
        // Assert
        expect(toasts.value).toEqual([{'info': message2}]);
    });
    it('stores a toast in local storage', () => {
        // Arrange
        const message = 'This is a stored toast';
        expect(toasts.value).toEqual([]);
        // Act
        storeToastInLocalStorage(message, 'error');
        // Assert
        expect(localStorage.getItem('queuedError')).toMatch(`{"type": "error", "message": "${message}"}`);
    });
    it('stores a toast in local storage and removes it, then reconstructs the toast', () => {
        // Arrange
        const message = 'This is a stored toast';
        expect(toasts.value).toEqual([]);
        // Act
        storeToastInLocalStorage(message, 'error');
        fetchStoredToasts();
        // Assert
        expect(toasts.value).toEqual([{'error': message}]);
    });

});