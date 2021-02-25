navigator.serviceWorker.register(URL_PATH + '/assets/script/helpers/sw.js');

const codeMessage = {
    200: "El servidor devolvió con éxito los datos solicitados. ",
    201: "Datos nuevos o modificados son exitosos. ",
    202: "Una solicitud ha ingresado a la cola de fondo (tarea asíncrona). ",
    204: "Eliminar datos con éxito. ",
    400: "La solicitud se envió con un error. El servidor no realizó ninguna operación para crear o modificar datos. ",
    401: "El usuario no tiene permiso (token, nombre de usuario, contraseña es incorrecta). ",
    403: "El usuario está autorizado, pero el acceso está prohibido. ",
    404: "La solicitud se realizó a un registro que no existe y el servidor no funcionó. ",
    406: "El formato de la solicitud no está disponible. ",
    410: "El recurso solicitado se elimina permanentemente y no se obtendrá de nuevo. ",
    422: "Al crear un objeto, se produjo un error de validación. ",
    500: "El servidor tiene un error, por favor revise el servidor. ",
    502: "Error de puerta de enlace. ",
    503: "El servicio no está disponible, el servidor está temporalmente sobrecargado o mantenido. ",
    504: "La puerta de enlace agotó el tiempo. ",
};

class RequestApi {
    static setHeaders(options) {
        if (!(options.body instanceof FormData)) {
            options.headers = {
                Accept: "application/json",
                "Content-Type": "application/json; charset=utf-8",
                ...options.headers,
            };
            options.body = JSON.stringify(options.body);
        } else {
            options.headers = {
                Accept: "application/json",
                ...options.headers,
            };
        }
        return options;
    }

    static checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response;
        }
        const errortext = codeMessage[response.status] || response.statusText;
        SnMessage.error({
            content: `Error de solicitud ${response.status}: ${response.url} ${errortext}`,
        });
        let error = new Error(errortext);
        error.name = response.status;
        error.response = response;
        throw error;
    }

    static fetch(path, options = {}) {
        NProgress.start();
        const newOptions = RequestApi.setHeaders(options);

        return fetch(URL_PATH + path, newOptions)
            .then(RequestApi.checkStatus)
            .then((response) => {
                return response.json();
            })
            .catch(e => {
                console.warn(e, 'FETCH_ERROR');
                return e;
            })
            .finally(e => {
                NProgress.done();
            });
    }

    static fetchOut(path, options = {}) {
        NProgress.start();
        const newOptions = RequestApi.setHeaders(options);

        return fetch(path, newOptions)
            .then(RequestApi.checkStatus)
            .then((response) => {
                return response.json();
            })
            .catch(e => {
                console.warn(e, 'FETCH_ERROR');
                return e;
            })
            .finally(e => {
                NProgress.done();
            });
    }
}

const printArea = function (idElem) {
    let dataTable = document.getElementById(idElem);
    if (dataTable) {
        let content = dataTable.outerHTML;
        let mywindow = window.open("", "Print", "height=600,width=800");

        mywindow.document.write("<html><head><title>Print</title>");
        mywindow.document.write("</head><body >");
        mywindow.document.write(content);
        mywindow.document.write("</body></html>");

        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
    }
};

const TableToExcel = (
    tableHtml,
    sheetName = "Sheet 1",
    fileName = "report"
) => {
    const template =
        '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>';
    const base64 = function (s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };
    const format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
        });
    };
    const s2ab = (s) => {
        let buf = new ArrayBuffer(s.length);
        let view = new Uint8Array(buf);
        for (let i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    };

    const ctx = { worksheet: sheetName, table: tableHtml };

    const blob = new Blob([s2ab(atob(base64(format(template, ctx))))], {
        type: "",
    });

    let link = document.createElement("a"); //console.log(nombreArchivo);
    link.download = fileName + ".xls";

    link.href = URL.createObjectURL(blob);
    link.click();
};

function geoGetCurrentPosition() {
    return new Promise((resolve, reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(userPosition => {
                let userLocation = {
                    lat: userPosition.coords.latitude,
                    lng: userPosition.coords.longitude,
                }
                resolve(userLocation);
            });
        } else {
            reject('geolocation not support');
        }
    });
}

// ------------------------------------------
function SnDropdown() {
    let lastDropdown = false;

    function toggleDropdown(listElem) {
        if (!listElem.classList.contains('show')) {
            listElem.classList.add('show');

            if (lastDropdown && lastDropdown !== listElem) {
                lastDropdown.classList.remove('show');
            }
            lastDropdown = listElem;
        } else {
            lastDropdown = false;
            listElem.classList.remove('show');
        }
    }

    function closeLastDropdown() {
        if (lastDropdown) {
            lastDropdown.classList.remove('show');
        }
    }

    document.querySelectorAll('.SnDropdown').forEach(item => {
        let toggleElem = item.querySelector('.SnDropdown-toggle');
        let listElem = toggleElem.nextElementSibling;

        if (!item.classList.contains('listen')) {
            item.classList.add('listen');
            toggleElem.addEventListener('click', e => {
                e.stopPropagation();
                toggleDropdown(listElem);
            }, true);
        }
    });

    window.addEventListener('click', e => {
        closeLastDropdown();
    });
}

function splitParagraphJsPDF(document, text, x1, y1, x2, textAlight = 'left', lineHeight = 7) {
    let pageInWidth = document.internal.pageSize.width;
    let lines = document.splitTextToSize(text, x2 - x1);
    // let dim = document.getTextDimensions('Text');
    // let lineHeight = dim.h;

    for (let i = 0; i < lines.length; i++) {
        let lineTop = (lineHeight / 2) * i;

        if (textAlight === 'center') {
            document.text(lines[i], pageInWidth / 2, y1 + lineTop, textAlight);
        } else if (textAlight === 'right') {
            document.text(lines[i], x2, y1 + lineTop, textAlight);
        } else {
            document.text(lines[i], x1, y1 + lineTop);
        }
    }

    let lastLine = (lineHeight / 2) * lines.length;
    return lastLine;
}


function numberToLetter(numberDecimal, uppercase, currencyId) {
    const getUnits = (uNumber) => {
        let units = ["", "un ", "dos ", "tres ", "cuatro ", "cinco ", "seis ", "siete ", "ocho ", "nueve "];
        return units[parseInt(uNumber)];
    }

    const getTens = (tNumber) => {
        let tens = ["diez ", "once ", "doce ", "trece ", "catorce ", "quince ", "dieciseis ", "diecisiete ", "dieciocho ", "diecinueve", "veinte ", "treinta ", "cuarenta ", "cincuenta ", "sesenta ", "setenta ", "ochenta ", "noventa "];
        // 99
        let tn = parseInt(tNumber);
        if (tn < 10) {
            return getUnits(tNumber);
        }
        else if (tn > 19) {
            let unitVal = parseInt(parseInt(tNumber) % 10);
            let ud = getUnits(unitVal);
            if (ud === "") {
                let tenVal = parseInt(parseInt(tNumber) / 10);
                return tens[parseInt(tenVal) + 8];
            } else {
                let tenVal = parseInt(parseInt(tNumber) / 10);
                return tens[parseInt(tenVal) + 8] + "y " + ud;
            }
        } else {
            return tens[tn - 10];
        }
    }

    const getHundreds = (hNumber) => {
        let hundreds = ["", "ciento ", "doscientos ", "trecientos ", "cuatrocientos ", "quinientos ", "seiscientos ", "setecientos ", "ochocientos ", "novecientos "];
        // 999 o 099
        if (parseInt(hNumber) > 99) {
            if (parseInt(hNumber) === 100) {
                return " cien ";
            } else {
                let cc = parseInt(parseInt(hNumber) / 100);
                let dd = parseInt(parseInt(hNumber) % 100);
                return hundreds[cc] + getTens(dd);
            }
        }
        else {
            return getTens(parseInt(hNumber));
        }
    }

    const getThousands = (thNumber) => {
        let cent = parseInt(parseInt(thNumber) % 1000);
        let m = parseInt(parseInt(thNumber) / 1000);
        let n = "";
        if (parseInt(m) > 0) {
            n = getHundreds(m);
            return n + "mil " + getHundreds(cent);
        }
        else {
            return "" + getHundreds(cent);
        }
    }

    const getMillions = (mNumber) => {
        let miles = parseInt(parseInt(mNumber) % 1000000);
        let millon = parseInt(parseInt(mNumber) / 1000000);
        let n = "";
        if (millon.Length > 1) {
            n = getHundreds(millon) + "millones ";
        }
        else {
            n = getUnits(millon) + "millon ";
        }
        return n + getThousands(miles);
    }

    let literal = "";
    let decimalPart = "";

    let number = numberDecimal.split('.');

    if (number.length > 1) {
        decimalPart = " con " + number[1] + "/100";
    } else {
        decimalPart = " con 00/100";
    }

    if (parseInt(number[0]) === 0) {
        literal = "cero ";
    }
    else if (parseInt(number[0]) > 999999) {
        literal = getMillions(number[0]);
    }
    else if (parseInt(number[0]) > 999) {
        literal = getThousands(number[0]);
    }
    else if (parseInt(number[0]) > 99) {
        literal = getHundreds(number[0]);
    }
    else if (parseInt(number[0]) > 9) {
        literal = getTens(number[0]);
    }
    else {
        literal = getUnits(number[0]);
    }
    let currencyName = "";
    if (parseInt(currencyId) === 1) {
        currencyName = " Soles";
    } else {
        currencyName = " Dolares";
    }

    if (uppercase) {
        return ((literal.trim() + decimalPart) + currencyName).toUpperCase();
    } else {
        return ((literal.trim() + decimalPart) + currencyName).toLowerCase();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    SnDropdown();
});
