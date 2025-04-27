const { MongoClient } = require('mongodb');

module.exports = async (req, res) => {
    if (req.method === 'POST') {
        const { license } = req.body;
        
        // Koneksi ke MongoDB
        const uri = 'mongodb+srv://HansDB:Hansmoses2007#@lisensi.98zue9l.mongodb.net/?retryWrites=true&w=majority';
        const client = new MongoClient(uri);
        
        try {
            await client.connect();
            const database = client.db('Lisensi');
            const collection = database.collection('Licenses');
            
            // Insert lisensi baru ke MongoDB
            await collection.insertOne({ license, created_at: new Date() });
            res.status(200).json({ success: true });
        } catch (error) {
            res.status(500).json({ success: false, message: error.message });
        } finally {
            await client.close();
        }
    } else {
        res.status(405).json({ success: false, message: 'Method Not Allowed' });
    }
};
