var form = document.getElementById('form')

form.addEventListener('submit', function() {
    // event.preventDefault();

    var kode         = document.getElementById('kode').value
    var nama         = document.getElementById('nama').value
    var alamat       = document.getElementById('alamat').value
    var tempat_lahir = document.getElementById('tempat_lahir').value
    var tgl_lahir    = document.getElementById('tgl_lahir').value
    var no_hp        = document.getElementById('no_hp').value
    var email        = document.getElementById('email').value
    var pekerjaan    = document.getElementById('pekerjaan').value
    var nik          = document.getElementById('nik').value
    var ktp          = document.getElementById('ktp').value
    var pdffile      = document.getElementById('pdffile').value

    if (kode.trim() === '') {
        return false;
    }

    if (nama.trim() === '') {
        return false;
    }

    if (alamat.trim() === '') {
        return false;
    }

    if (tempat_lahir.trim() === '') {
        return false;
    }
    
    if (tgl_lahir.trim() === '') {
        return false;
    }
    
    if (no_hp.trim() === '') {
        return false;
    }
    
    if (email.trim() === '') {
        return false;
    }

    if (pekerjaan.trim() === '') {
        return false;
    }

    if (nik.trim() === '') {
        return false;
    }

    if (ktp.trim() === '') {
        return false;
    }

    if (pdffile.trim() === '') {
        return false;
    }

    var doc = new jsPDF()

    doc.text('Kode Permohonan : ' + kode, 10, 10);
    doc.text('Nama Lengkap : ' + nama, 10, 20);
    doc.text('Alamat : ' + alamat, 10, 30);
    doc.text('Tempat Lahir : ' + tempat_lahir, 10, 40);
    doc.text('Tanggal Lahir : ' + tgl_lahir, 10, 50);
    doc.text('No Hp : ' + no_hp, 10, 60);
    doc.text('Email : ' + email, 10, 70);
    doc.text('Pekerjaan : ' + pekerjaan, 10, 80);
    doc.text('+628112735100', 10, 110);
    doc.text('dinpertaru@jogjakota.go.id', 80, 110);


    doc.save("Permohonan-" + kode + ".pdf");
});