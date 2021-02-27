function paymentPrint(paymentId) {
	RequestApi.fetch("/admin/report/paymentPrint", {
		method: "POST",
		body: {
			paymentId: paymentId || 0,
		},
	})
		.then((res) => {
			if (res.success) {
				paymentPrintRender(res.result);
			} else {
				SnModal.error({ title: "Algo salió mal", content: res.message });
			}
		})
		.finally((e) => {
			//   customerSetLoading(false);
		});
}

function addImageProcess(src) {
	return new Promise((resolve, reject) => {
		let img = new Image()
		img.onload = () => resolve(img)
		img.onerror = reject
		img.src = src
	})
}

async function paymentPrintRender(result) {
	let payment = result.payment;
	let company = result.company;
	let currentDate = result.currentDate;
	moment.locale('es');

	SnModal.open('pdfPrintModal');

	let marginLeft = 5; //left margin in mm
	let marginRight = 5; //right margin in mm
	let pageWidth = 210;  // width of A4 in mm
	let pageY = 0;

	let doc = new jsPDF('p', 'mm');
	doc.setFontSize(10);
	doc.setFont("helvetica");

	// LEFT
	let img = null;
	if (company.logo_large.length > 3) {
		img = await addImageProcess(URL_PATH + company.logo_large);
	}

	let pageWidth1 = (pageWidth / 2);

	// PAGE 1
	pageY = 4;
	doc.setFontType('bold');

	let imageWidth = 60;
	let imageWidthSpace = (pageWidth1 - imageWidth) / 2;
	if (company.logo_large.length > 3 && img != null) {
		let extencion = company.logo_large.split('.').pop();
		doc.addImage(img, extencion, imageWidthSpace, pageY, imageWidth, pageY + 10);
		pageY += 20;
	} else {
		pageY += 2;
		doc.setFontSize(12);
		pageY += splitParagraphJsPDF(doc, company.commercial_reason, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);
		pageY += 2;
	}

	doc.setFontSize(8);
	pageY += splitParagraphJsPDF(doc, company.social_reason, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	pageY += 1;
	doc.setFontSize(11);
	pageY += splitParagraphJsPDF(doc, 'DOC: ' + company.document_number, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	doc.setFontSize(8);
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, company.fiscal_address.trim(), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	pageY += splitParagraphJsPDF(doc, 'Telefono: ' + company.phone.trim(), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	pageY -= 2;
	doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

	pageY += 5;
	doc.setFontType('bold');
	doc.setFontSize(11);
	pageY += splitParagraphJsPDF(doc, 'NRO. OP: ' + payment.payment_id, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	pageY -= 1;
	doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

	pageY += 4;
	doc.setFontType('normal');
	doc.setFontSize(8);
	doc.text(marginLeft, pageY, 'CLIENTE: ');

	doc.setFontType('bold');
	pageY += splitParagraphJsPDF(doc, payment.customer_social_reason, marginLeft + 14, pageY, (pageWidth1 - marginRight), 'left', 7);

	doc.setFontType('normal');
	doc.text(marginLeft, pageY, 'DOC: '); doc.setFontType('bold'); doc.text(marginLeft + 8, pageY, payment.customer_document_number);

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'FECHA DE PAGO:'); doc.setFontType('bold'); doc.text(marginLeft + 25, pageY, payment.datetime_of_issue);

	pageY += 2;
	doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

	pageY += 4;
	doc.setFontType('bold'); doc.text(marginLeft, pageY, 'CICLO PAGO.');

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'FECHA DE INICIO:'); doc.setFontType('bold'); doc.text(marginLeft + 26, pageY, moment(payment.from_datetime).format('LL'));

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'FECHA DE VENCIMIENTO:'); doc.setFontType('bold'); doc.text(marginLeft + 37, pageY, moment(payment.to_datetime).format('LL'));

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'DESCRIPCIÓN:'); doc.setFontType('bold'); doc.text(marginLeft + 22, pageY, payment.description);

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'MESES:'); doc.setFontType('bold'); doc.text(marginLeft + 12, pageY, payment.payment_count);

	pageY += 3;
	doc.setFontType('normal'); doc.text(marginLeft, pageY, 'TOTAl A PAGAR:'); doc.setFontType('bold'); doc.text(marginLeft + 24, pageY, 'S/.' + payment.total);

	// pageY += 3;
	// doc.setFontType('normal'); doc.text(marginLeft, pageY, 'DEUDAS:'); doc.setFontType('bold'); doc.text(marginLeft + 14, pageY, 'S/.' + 0.00);

	pageY += 3;
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, numberToLetter(parseFloat(payment.total).toFixed(2), true, 1), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	pageY -= 2;
	doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

	pageY += 4;
	pageY += splitParagraphJsPDF(doc, 'VERIFIQUE SU VÁUCHER Y SU DINERO ANTES DE RETIRARSE DE LA VENTANILLA', marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	// doc.setFontSize(8);
	pageY += splitParagraphJsPDF(doc, 'USUARIO: ' + payment.user_name, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);
	pageY += splitParagraphJsPDF(doc, 'FECHA IMPRESIÓN: ' + currentDate, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

	// pageY += 5;
	doc.setFontSize(7);
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, 'soportado por https://paulantezana.com', marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);



	// PAGE 2
	pageY = 4;
	offsetLeft = pageWidth1;
	doc.setFontType('bold');

	if (company.logo_large.length > 3 && img != null) {
		let extencion = company.logo_large.split('.').pop();
		doc.addImage(img, extencion, offsetLeft + imageWidthSpace, pageY, imageWidth, pageY + 10);
		pageY += 20;
	} else {
		pageY += 2;
		doc.setFontSize(12);
		pageY += splitParagraphJsPDF(doc, company.commercial_reason, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);
		pageY += 2;
	}

	doc.setFontSize(8);
	pageY += splitParagraphJsPDF(doc, company.social_reason, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY += 1;
	doc.setFontSize(11);
	pageY += splitParagraphJsPDF(doc, 'DOC: ' + company.document_number, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	// pageY += 3;
	doc.setFontSize(8);
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, company.fiscal_address.trim(), offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY += splitParagraphJsPDF(doc, 'Telefono: ' + company.phone.trim(), offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY -= 2;
	doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

	pageY += 5;
	doc.setFontType('bold');
	doc.setFontSize(11);
	pageY += splitParagraphJsPDF(doc, 'NRO. OP: ' + payment.payment_id, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY -= 1;
	doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

	pageY += 4;
	doc.setFontType('normal');
	doc.setFontSize(8);
	doc.text(offsetLeft + marginLeft, pageY, 'CLIENTE: ');

	doc.setFontType('bold');
	pageY += splitParagraphJsPDF(doc, payment.customer_social_reason, offsetLeft + marginLeft + 14, pageY, (pageWidth - marginRight), 'left', 7);

	doc.setFontType('normal');
	doc.text(offsetLeft + marginLeft, pageY, 'DOC: '); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 8, pageY, payment.customer_document_number);

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'FECHA DE PAGO: '); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 24, pageY, payment.datetime_of_issue);

	pageY += 2;
	doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

	pageY += 4;
	doc.setFontType('bold'); doc.text(offsetLeft + marginLeft, pageY, 'CICLO PAGO.');

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'FECHA DE INICIO:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 26, pageY, moment(payment.from_datetime).format('LL'));

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'FECHA DE VENCIMIENTO:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 37, pageY, moment(payment.to_datetime).format('LL'));

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'DESCRIPCIÓN:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 22, pageY, payment.description);

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'MESES:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 12, pageY, payment.payment_count);

	pageY += 3;
	doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'TOTAl A PAGAR:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 24, pageY, 'S/.' + payment.total);

	// pageY += 3;
	// doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'DEUDAS:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 14, pageY, 'S/.' + 0.00);

	pageY += 3;
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, numberToLetter(parseFloat(payment.total).toFixed(2), true, 1), offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY -= 2;
	doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

	pageY += 4;
	pageY += splitParagraphJsPDF(doc, 'VERIFIQUE SU VÁUCHER Y SU DINERO ANTES DE RETIRARSE DE LA VENTANILLA', offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	pageY += splitParagraphJsPDF(doc, 'USUARIO: ' + payment.user_name, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);
	pageY += splitParagraphJsPDF(doc, 'FECHA IMPRESIÓN: ' + currentDate, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	// pageY += 5;
	doc.setFontSize(7);
	doc.setFontType('normal');
	pageY += splitParagraphJsPDF(doc, 'soportado por https://paulantezana.com', offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1 / 2) * 3);

	let stringPDF = doc.output('bloburl');
	let pdfPrintIframe = document.getElementById('pdfPrintIframe');
	pdfPrintIframe.innerHTML = `<iframe src="${stringPDF}" frameborder="0" style="width: 100%; height: 30rem"></iframe>`;
}