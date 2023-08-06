async function dataUserToPDF() {
    const data = await fetch("../includes/datauser.php");
    const textData = await data.text();
  
    const { jsPDF } = window.jspdf;
    let dataPDF = new jsPDF();
    dataPDF.setFontSize(11);
    const textLines = dataPDF.splitTextToSize(
      textData,
      dataPDF.internal.pageSize.width - 25
    );
    dataPDF.text(textLines, 10, 10);
    dataPDF.save("donnée.pdf");
  }
  
