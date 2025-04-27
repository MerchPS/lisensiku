const { MongoClient } = require('mongodb');

// MongoDB connection URI and database details
const mongoURI = "mongodb+srv://HansDB:Hansmoses2007#@cluster0.mongodb.net";
const dbName = "licensingDB";
const collectionName = "licenses";

// Connect to MongoDB and add the license
const client = new MongoClient(mongoURI, { useNewUrlParser: true, useUnifiedTopology: true });

module.exports = async (req, res) => {
    if (req.method === 'POST') {
        const { license } = req.body;

        if (!license) {
            return res.status(400).json({ message: 'Lisensi tidak boleh kosong' });
        }

        try {
            await client.connect();
            const db = client.db(dbName);
            const collection = db.collection(collectionName);

            // Insert the new license into MongoDB
            const result = await collection.insertOne({ license });

            // Respond with success
            res.status(200).json({ success: true, message: 'Lisensi berhasil ditambahkan!' });
        } catch (error) {
            console.error('Database connection error:', error);
            res.status(500).json({ success: false, message: 'Terjadi kesalahan di server.' });
        } finally {
            await client.close();
        }
    } else {
        // Handle non-POST requests
        res.status(405).json({ message: 'Method Not Allowed' });
    }
};
