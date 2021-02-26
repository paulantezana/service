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

function paymentPrintRender(result) {
	let payment = result.payment;
	let company = result.company;

	SnModal.open('pdfPrintModal');

	let marginLeft = 5; //left margin in mm
	let marginRight = 5; //right margin in mm
	let pageWidth = 210;  // width of A4 in mm
	let pageY = 0;

	let doc = new jsPDF('p', 'mm');
	doc.setFontSize(10);
	doc.setFont("helvetica");
	if (company.logo_large.trim() == '') {
		SnModal.error({ title: "Algo salió mal", content: 'No se configuró ningun logo' });
		return;
	}

	// LEFT
	let img = new Image();
	img.src = URL_PATH + company.logo_large;
	img.onload = function () {
		let extencion = company.logo_large.split('.').pop();
		let pageWidth1 = (pageWidth / 2);

		// PAGE 1
		pageY = 4;
		let imageWidth = 60;
		let imageWidthSpace = (pageWidth1 - imageWidth) / 2;
		doc.addImage(img, extencion, imageWidthSpace, pageY, imageWidth, pageY + 10);
		doc.setFontSize(8);
		doc.setFontType('bold');

		pageY += 20;
		pageY += splitParagraphJsPDF(doc, company.social_reason, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7, pageWidth1 / 2);

		pageY += 1;
		doc.setFontSize(11);
		pageY += splitParagraphJsPDF(doc, 'RUC: ' + company.document_number, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);

		doc.setFontSize(8);
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, company.fiscal_address.trim(), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);

		pageY += splitParagraphJsPDF(doc, 'Telefono: ' + company.phone.trim(), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);

		pageY += 1;
		doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

		pageY += 6;
		doc.setFontType('bold');
		doc.setFontSize(14);
		pageY += splitParagraphJsPDF(doc, 'COD: ' + payment.payment_id, marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);

		pageY -= 1;
		doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

		pageY += 5;
		doc.setFontType('normal');
		doc.setFontSize(8);
		doc.text(marginLeft, pageY, 'DENOMINACIÓN: ');

		doc.setFontType('bold');
		pageY += splitParagraphJsPDF(doc, payment.customer_social_reason, marginLeft + 24, pageY, (pageWidth1 - marginRight), 'left', 7);

		doc.setFontType('normal');
		doc.text(marginLeft, pageY, 'DOC: '); doc.setFontType('bold'); doc.text(marginLeft + 8, pageY, payment.customer_document_number);

		pageY += 4;
		doc.setFontType('normal'); doc.text(marginLeft, pageY, 'F. EMISION:'); doc.setFontType('bold'); doc.text(marginLeft + 17, pageY, payment.datetime_of_issue);

		pageY += 4;
		doc.setFontType('normal'); doc.text(marginLeft, pageY, 'DESCRIPCIÓN:'); doc.setFontType('bold'); doc.text(marginLeft + 21, pageY, payment.description);

		pageY += 3;
		doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

		pageY += 5;
		doc.text('TOTAL ', marginLeft + 30, pageY, 'right'); doc.text(marginLeft + 50, pageY, 'S/'); doc.text(payment.total, (pageWidth1 - marginRight), pageY, 'right');

		pageY += 3;
		doc.line(marginLeft, pageY, (pageWidth1 - marginRight), pageY);

		pageY += 4;
		doc.setFontType('normal');
		doc.setFontSize(8);
		pageY += splitParagraphJsPDF(doc, numberToLetter(parseFloat(payment.total).toFixed(2), true, 1), marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);
		pageY += splitParagraphJsPDF(doc, 'user: ' + payment.user_name , marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);

		// pageY += 5;
		doc.setFontSize(6);
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, 'soportado por https://paulantezana.com', marginLeft, pageY, (pageWidth1 - marginRight), 'center', 7,pageWidth1 / 2);



		// PAGE 2
		pageY = 4;
		offsetLeft = pageWidth1;
		doc.addImage(img, extencion, offsetLeft + imageWidthSpace, pageY, imageWidth, pageY + 10);
		doc.setFontSize(8);
		doc.setFontType('bold');

		pageY += 20;
		pageY += splitParagraphJsPDF(doc, company.social_reason, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7, (pageWidth1/2)*3);

		pageY += 1;
		doc.setFontSize(11);
		pageY += splitParagraphJsPDF(doc, 'RUC: ' + company.document_number, offsetLeft + marginLeft, pageY, (pageWidth1 * 3 - marginRight), 'center', 7,(pageWidth1/2)*3);

		// pageY += 4;
		doc.setFontSize(8);
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, company.fiscal_address.trim(), offsetLeft + marginLeft, pageY, (pageWidth1 * 3 - marginRight), 'center', 7,(pageWidth1/2)*3);

		pageY += splitParagraphJsPDF(doc, 'Telefono: ' + company.phone.trim(), offsetLeft + marginLeft, pageY, (pageWidth1 * 3 - marginRight), 'center', 7,(pageWidth1/2)*3);

		pageY += 1;
		doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 6;
		doc.setFontType('bold');
		doc.setFontSize(14);
		pageY += splitParagraphJsPDF(doc, 'COD: ' + payment.payment_id, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7,(pageWidth1/2)*3);

		pageY -= 1;
		doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 5;
		doc.setFontType('normal');
		doc.setFontSize(8);
		doc.text(offsetLeft + marginLeft, pageY, 'DENOMINACIÓN: ');

		doc.setFontType('bold');
		pageY += splitParagraphJsPDF(doc, payment.customer_social_reason, offsetLeft + marginLeft + 24, pageY, (pageWidth - marginRight), 'left', 7);

		doc.setFontType('normal');
		doc.text(offsetLeft + marginLeft, pageY, 'DOC: '); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 8, pageY, payment.customer_document_number);

		pageY += 4;
		doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'F. EMISION:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 17, pageY, payment.datetime_of_issue);

		pageY += 4;
		doc.setFontType('normal'); doc.text(offsetLeft + marginLeft, pageY, 'DESCRIPCIÓN:'); doc.setFontType('bold'); doc.text(offsetLeft + marginLeft + 21, pageY, payment.description);

		pageY += 3;
		doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 5;
		doc.text('TOTAL ', offsetLeft + marginLeft + 30, pageY, 'right'); doc.text(offsetLeft + marginLeft + 50, pageY, 'S/'); doc.text(payment.total, (pageWidth - marginRight), pageY, 'right');

		pageY += 3;
		doc.line(offsetLeft + marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 4;
		doc.setFontType('normal');
		doc.setFontSize(8);
		pageY += splitParagraphJsPDF(doc, numberToLetter(parseFloat(payment.total).toFixed(2), true, 1), offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7,(pageWidth1/2)*3);
		pageY += splitParagraphJsPDF(doc, 'user: ' + payment.user_name, offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7,(pageWidth1/2)*3);

		// pageY += 5;
		doc.setFontSize(6);
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, 'soportado por https://paulantezana.com', offsetLeft + marginLeft, pageY, (pageWidth - marginRight), 'center', 7,(pageWidth1/2)*3);

		let stringPDF = doc.output('bloburl');
		let pdfPrintIframe = document.getElementById('pdfPrintIframe');
		pdfPrintIframe.innerHTML = `<iframe src="${stringPDF}" frameborder="0" style="width: 100%; height: 30rem"></iframe>`;
	}
}