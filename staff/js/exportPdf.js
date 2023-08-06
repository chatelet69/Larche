const { jsPDF } = window.jspdf;
//const { autoTable } = window.jspdf-autotable;
let userPdf = new jsPDF();

async function exportPdf(userToExport) {
    try {
        let request = await fetch(`../includes/staff-options.php?requestType=exportPdf&userToExport=${userToExport}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/module/gestion-membres.php",
        });
        let userData = await request.json();
        let data = [];
        Object.keys(userData).forEach((key) => {
            data.push(
                { 
                    content: key,
                    data: userData[`${key}`]
                });
        });
        userPdf.addPage();
        userPdf.text(`Informations utilisateurs de ${userData.pseudo}`, (userPdf.internal.pageSize.width / 2), 10, { 'align': 'center' });
        userPdf.autoTable({
            body: data,
            tableWidth: "wrap",
            styles: {
                orientation: "landscape",
                fontSize: 12,
                lineColor: [128, 128, 128],
                lineWidth: 0.2,
                fontStyle: 'bold',
                textColor: '#007bff',
                align: 'center',
                overflow: 'linebreak',
                margin: { top: 40 },
                startY: 20,
                cellWidth: 'wrap',
            },
            bodyStyles: {
                overflow: 'linebreak',
            },
            columnStyles: {
                1: {cellWidth: 120}
            }
        });
        userPdf.deletePage(userPdf.internal.getNumberOfPages()-1);
        userPdf.save(`${userData.pseudo}-pdf.pdf`);
        userPdf.deletePage(userPdf.internal.getNumberOfPages());
    } catch (error) {
        console.log(error);
        alert("Problème dans l'export, réessayez plus tard");
    }
}