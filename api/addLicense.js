const { MongoClient } = require('mongodb');

module.exports = async (req, res) => {
    if (req.method === 'POST') {
        const { license } = req.body;

        if (!license) {
            return res.status(400).json({ success: false, message: 'Lisensi tidak boleh kosong' });
        }

        // Koneksi ke MongoDB
        const uri = 'mongodb+srv://HansDB:Hansmoses2007#@lisensi.98zue9l.mongodb.net/?retryWrites=true&w=majority';
        const client = new MongoClient(uri);

        try {
            // Coba connect ke MongoDB
            await client.connect();
            const database = client.db('Lisensi');
            const collection = database.collection('Licenses');

            // Insert lisensi baru ke MongoDB
            await collection.insertOne({ license, created_at: new Date() });
            console.log('Lisensi berhasil ditambahkan:', license);  // Log untuk memastikan lisensi berhasil ditambahkan
            res.status(200).json({ success: true, message: 'Lisensi berhasil ditambahkan!' });
        } catch (error) {
            console.error('Error saat menyimpan lisensi:', error);  // Log error untuk debugging
            res.status(500).json({ success: false, message: 'Terjadi kesalahan di server: ' + error.message });
        } finally {
            await client.close();
        }
    } else {
        res.status(405).json({ success: false, message: 'Method Not Allowed' });
    }
};
