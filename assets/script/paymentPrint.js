function paymentPrint(paymentId){
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

function paymentPrintRender(result){
	let payment = result.payment;
	let company = result.company;

    SnModal.open('pdfPrintModal');

	let marginLeft = 3; //left margin in mm
	let marginRight = 3; //right margin in mm
	let pageWidth = 80;  // width of A4 in mm
	let pageY = 0;

	let doc = new jsPDF('p', 'mm', [220, pageWidth]);
	doc.setFontSize(10);
	doc.setFont("helvetica");
	if(company.logo_large.trim() == ''){
		SnModal.error({ title: "Algo salió mal", content: 'No se configuró ningun logo' });
		return;
	}
	let img = new Image();
	img.src = URL_PATH + company.logo_large;
	img.onload = function(){
		let extencion = company.logo_large.split('.').pop();
		doc.addImage(img, extencion, 14, 0, 55, 15);
		doc.setFontSize(8);
		doc.setFontType('bold');

		pageY = 20;
		pageY += splitParagraphJsPDF(doc, company.social_reason, 3, pageY, (pageWidth - marginRight), 'center', 7);

		pageY += 1;
		doc.setFontSize(11);
		doc.text(24, pageY, 'RUC: ' + company.document_number);

		pageY += 4;
		doc.setFontSize(8);
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, company.fiscal_address.trim(), 3, pageY, (pageWidth - marginRight), 'center', 7);

		// pageY += splitParagraphJsPDF(doc, `${company.district} - ${company.province} - ${company.department}`, 3, pageY, (pageWidth - marginRight), 'center', 7);
		// pageY += splitParagraphJsPDF(doc, company.HeaderPDF || '', 3, pageY, (pageWidth - marginRight), 'center', 7);
		
		pageY += 1;
		doc.line(marginLeft, pageY, (pageWidth - marginRight), pageY);
		
		pageY += 5;
		doc.setFontSize(10);
		doc.text(7, pageY,  'FOLIO');

		pageY += 5;
		doc.setFontType('bold');
		doc.setFontSize(14);
		doc.text(21, pageY, payment.reference);

		pageY += 2;
		doc.line(marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 5;
		doc.setFontType('normal');
		doc.setFontSize(8);
		pageY += splitParagraphJsPDF(doc, payment.customer_social_reason, marginLeft, pageY, (pageWidth - marginRight), 'left', 7);

		doc.text(marginLeft, pageY,'DOC: '); doc.setFontType('bold'); doc.text(marginLeft + 8, pageY, payment.customer_document_number);

		pageY += 5;
		doc.setFontType('normal'); doc.text(marginLeft, pageY,'F. EMISION:'); doc.setFontType('bold'); doc.text(marginLeft + 17, pageY, payment.datetime_of_issue);

		// pageY += 2;
		// doc.line(marginLeft, pageY, (pageWidth - marginRight), pageY);

		// pageY += 5;
		// doc.text('GRAVADA ', 40, pageY, 'right'); doc.text(50, pageY, 'S/ '); doc.text(facturacion.Gravado, (pageWidth - marginRight), pageY, 'right');

		// pageY += 5;
		// doc.text('IGV ', 40, pageY, 'right'); doc.text(50, pageY, 'S/'); doc.text(facturacion.Igv, (pageWidth - marginRight), pageY, 'right');

		pageY += 5;
		doc.text('TOTAL ', 40, pageY, 'right'); doc.text(50, pageY, 'S/'); doc.text(payment.total, (pageWidth - marginRight), pageY, 'right');

		pageY += 3;
		doc.line(marginLeft, pageY, (pageWidth - marginRight), pageY);

		pageY += 4;
		doc.setFontType('normal'); 
		doc.setFontSize(8);
		pageY += splitParagraphJsPDF(doc, numberToLetter(parseFloat(payment.total).toFixed(2),true,1), marginLeft, pageY, (pageWidth - marginRight), 'center', 7);
		// pageY += splitParagraphJsPDF(doc, 'Counter: ' + pasajero.UsuarioNombres, marginLeft, pageY, (pageWidth - marginRight), 'center', 7);


		pageY += 5;
		doc.setFontType('normal');
		pageY += splitParagraphJsPDF(doc, 'soportado por https://paulantezana.com', marginLeft, pageY, (pageWidth - marginRight), 'center', 7);

		let stringPDF = doc.output('bloburl');
		let pdfPrintIframe = document.getElementById('pdfPrintIframe');
		pdfPrintIframe.innerHTML = `<iframe src="${stringPDF}" frameborder="0" style="width: 100%; height: 30rem"></iframe>`;
	}
}