const { MongoClient, ServerApiVersion } = require('mongodb');

// Direct MongoDB URI connection string with URL-encoded '#'
const uri = "mongodb+srv://HansDB:Hansmoses2007%23@lisensi.98zue9l.mongodb.net/?retryWrites=true&w=majority&appName=Lisensi";

const client = new MongoClient(uri, {
  serverApi: {
    version: ServerApiVersion.v1,
    strict: true,
    deprecationErrors: true,
  }
});

module.exports = async (req, res) => {
    if (req.method === 'POST') {
        const { license } = req.body;

        if (!license) {
            return res.status(400).json({ success: false, message: 'Lisensi tidak boleh kosong' });
        }

        try {
            // Log the connection attempt
            console.log('Attempting to connect to MongoDB...');
            await client.connect();

            const db = client.db("licensingDB"); // Database name
            const collection = db.collection("licenses"); // Collection name

            // Insert the license into MongoDB
            const result = await collection.insertOne({ license });

            // Log the result
            console.log(`License added: ${license}`);

            // Return success response
            res.status(200).json({ success: true, message: 'Lisensi berhasil ditambahkan!' });
        } catch (error) {
            // Log the error details for debugging
            console.error('Database connection error:', error);
            res.status(500).json({ success: false, message: 'Terjadi kesalahan di server.' });
        } finally {
            // Ensure MongoDB connection is closed
            await client.close();
        }
    } else {
        // Handle non-POST requests
        res.status(405).json({ success: false, message: 'Method Not Allowed' });
    }
};
