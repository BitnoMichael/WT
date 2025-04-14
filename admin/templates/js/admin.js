const currentPathElement = document.getElementById('currentPath');
const fileListElement = document.getElementById('fileList');
const storageInfoElement = document.getElementById('storageInfo');
const modalContainer = document.getElementById('modalContainer');
const fileInput = document.getElementById('fileInput');
const status = document.getElementById('statusMessage');
function setStatusMessage(message) {
    status.innerHTML = message;
}

let currentPath = 'public/templates/'; // Начальная директория

document.getElementById('createFolder').addEventListener('click', createFolderModal);
document.getElementById('uploadFile').addEventListener('click', () => fileInput.click());
document.getElementById('refresh').addEventListener('click', loadFiles);

fileInput.addEventListener('change', uploadFile);
function updateCurrentPathDisplay() {
    currentPathElement.textContent = currentPath;
}


function loadFiles() {
    fetch(`/admin/api/files?path=${currentPath}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Ошибка HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            renderFiles(data);
        })
        .catch(error => {
            console.error('Ошибка при загрузке файлов:', error);
        });
}

function renderFiles(files) {
    fileListElement.innerHTML = '';
    
    if (currentPath !== 'public/templates/') {
        const parentDirRow = document.createElement('div');
        parentDirRow.className = 'file-table-row';
        parentDirRow.innerHTML = `
            <div class="file-name-col">
                <div class="file-item">
                    <i class="fas fa-level-up-alt file-icon"></i>
                    <a href="#" onclick="navigate('..')">..</a>
                </div>
            </div>
            <div class="file-size-col"></div>
            <div class="file-modified-col"></div>
            <div class="file-actions-col"></div>
        `;
        fileListElement.appendChild(parentDirRow);
    }

    files.forEach(file => {
        const fileRow = document.createElement('div');
        fileRow.className = 'file-table-row';

        const isDirectory = file.type === 'directory';
        const iconClass = isDirectory ? 'fa-folder' : 'fa-file';

        fileRow.innerHTML = `
            <div class="file-name-col">
                <div class="file-item">
                    <i class="fas ${iconClass} file-icon"></i>
                    ${isDirectory?`<a href="#" onclick="navigate('${file.name}')">${file.name}</a>`:`<b>${file.name}</b>`}
                </div>
            </div>
            <div class="file-size-col">${isDirectory ? '-' : formatBytes(file.size)}</div>
            <div class="file-modified-col">${file.modified}</div>
            <div class="file-actions-col">
                ${isDirectory ? '' : `<button class="file-action-btn edit" onclick="editFile('${currentPath}${file.name}')"><i class="fas fa-edit"></i></button>`}
                ${isDirectory ? '' : `<button class="file-action-btn download" onclick="downloadFile('${currentPath}${file.name}')"><i class="fas fa-download"></i></button>`}
                <button class="file-action-btn delete" onclick="deleteItem('${currentPath}${file.name}', '${file.type}')"><i class="fas fa-trash"></i></button>
            </div>
        `;
        fileListElement.appendChild(fileRow);
    });
}

function navigate(name) {
    if (name === '..') {
        const pathParts = currentPath.split('/').filter(part => part !== '');
        pathParts.pop();
        currentPath = pathParts.join('/') + '/';
    } else {
        currentPath += name + '/';
    }

    updateCurrentPathDisplay();
    loadFiles();
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function createFolderModal() {
    const modal = createModal('Создать новую папку', `
        <div class="form-group">
            <label for="folderName" class="form-label">Имя папки:</label>
            <input type="text" class="form-control" id="folderName" placeholder="Введите имя папки">
        </div>
    `, 'Создать');

    modal.querySelector('button[type="submit"]').addEventListener('click', (e) => {
        e.preventDefault();
        const folderName = document.getElementById('folderName').value;
        if (folderName) {
            createFolder(folderName);
            closeModal();
        } else {
            alert('Пожалуйста, введите имя папки.');
        }
    });
}

function createFolder(folderName) {
    fetch('/admin/api/createFolder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            path: currentPath,
            name: folderName
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            setStatusMessage(`Папка "${folderName}" создана.`);
            loadFiles();
        } else {
            setStatusMessage(`Ошибка при создании папки: ${data.error}`);
        }
    })
    .catch(error => {
        console.error('Ошибка при создании папки:', error);
        setStatusMessage('Ошибка сети при создании папки.');
    });
}

function uploadFile() {
    console.log("Hello, honey!");
    const file = fileInput.files[0];
    if (!file) 
        return;

    fetch('/admin/api/uploadFile', { 
        method: 'POST',
        body: JSON.stringify({
            file: file,
            path: currentPath
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            setStatusMessage(`Файл "${file.name}" загружен.`);
            loadFiles();
        } else {
            setStatusMessage(`Ошибка при загрузке файла: ${data.error}`);
        }
    })
    .catch(error => {
        console.error('Ошибка при загрузке файла:', error);
        setStatusMessage('Ошибка сети при загрузке файла.');
    });
}

function editFile(filePath) {
    fetch(`/admin/api/fileContent?path=${filePath}`) // Замените на ваш эндпоинт
        .then(response => {
            if (!response.ok) {
                throw new Error(`Ошибка HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then(content => {
            const modal = createModal(`Редактирование: ${filePath.split('/').pop()}`, `
                <div class="form-group">
                    <textarea class="form-control textarea-code" id="fileContent" rows="15">${content}</textarea>
                </div>
            `, 'Сохранить');

            modal.querySelector('button[type="submit"]').addEventListener('click', (e) => {
                e.preventDefault();
                const newContent = document.getElementById('fileContent').value;
                saveFile(filePath, newContent);
                closeModal();
            });
        })
        .catch(error => {
            console.error('Ошибка при получении содержимого файла:', error);
            setStatusMessage('Ошибка при получении содержимого файла.');
        });
}

function saveFile(filePath, content) {
    fetch('/admin/api/save', { // Замените на ваш эндпоинт
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            path: filePath,
            content: content
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            setStatusMessage(`Файл "${filePath.split('/').pop()}" сохранен.`);
            loadFiles();
        } else {
            setStatusMessage(`Ошибка при сохранении файла: ${data.error}`);
        }
    })
    .catch(error => {
        console.error('Ошибка при сохранении файла:', error);
        setStatusMessage('Ошибка сети при сохранении файла.');
    });
}

async function downloadFile(filePath) {
    try {
        // 1. Отправляем GET-запрос с path в URL (не в body!)
        const response = await fetch(`/admin/api/download?path=${encodeURIComponent(filePath)}`);
        
        if (!response.ok) {
            throw new Error(`Ошибка: ${response.status}`);
        }

        // 2. Получаем файл как Blob
        const blob = await response.blob();
        
        // 3. Создаем временную ссылку для скачивания
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filePath.split('/').pop(); // Имя файла
        a.click();
        
        // 4. Освобождаем память
        setTimeout(() => URL.revokeObjectURL(url), 100);
        
        console.log('Файл скачивается');
    } catch (error) {
        console.error('Ошибка:', error);
    }
}

function deleteItem(path, type) {
    const name = path.split('/').pop();
    const itemType = type === 'directory' ? 'папку' : 'файл';

    if (confirm(`Вы уверены, что хотите удалить ${itemType} "${name}"?`)) {
        fetch('/admin/api/delete', { // Замените на ваш эндпоинт
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                path: path,
                type: type
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Ошибка HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                setStatusMessage(`${type === 'directory' ? 'Папка' : 'Файл'} "${name}" удален.`);
                loadFiles();
            } else {
                setStatusMessage(`Ошибка при удалении: ${data.error}`);
            }
        })
        .catch(error => {
            console.error('Ошибка при удалении:', error);
            setStatusMessage('Ошибка сети при удалении.');
        });
    }
}


function createModal(title, body, submitText) {
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';

    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    modalContent.innerHTML = `
        <div class="modal-header">
            <div class="modal-title">${title}</div>
            <button class="modal-close">&times;</button>
        </div>
        <form class="modal-body">${body}</form>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modal-close">Отмена</button>
            <button type="submit" class="btn btn-primary">${submitText}</button>
        </div>
    `;

    modalOverlay.appendChild(modalContent);
    modalContainer.appendChild(modalOverlay);

    
    modalOverlay.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    return modalContent;
}

function closeModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        modal.remove();
    }
}


window.onload = () => {
    updateCurrentPathDisplay();
    loadFiles();
};
