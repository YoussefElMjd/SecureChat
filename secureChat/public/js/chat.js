/**
 * Allows to import a public key and return a encrypt data
 * @param {String} str the data
 * @param {String} public_key the public key
 * @returns encrypt data in base 64
 */
async function importPublicKeyAndEncrypt(str, public_key) {
    try {
        const pub = await importPublicKey(public_key);
        const encrypted = await encryptRSA(pub, new TextEncoder().encode(str));
        const encryptedBase64 = window.btoa(ab2str(encrypted));
        //console.log(encryptedBase64.replace(/(.{64})/g, '$1\n'));
        return encryptedBase64;
    } catch (error) {
        console.log(error);
    }
}
/**
 * Allows to import a private key and return a decrypt data
 * @param {String} str the data
 * @param {String} private_key the private key
 * @returns decrypt data in plain text
 */
async function importPrivateKeyAndDecrypt(str, private_key) {
    try {
        const priv = await importPrivateKey(private_key);
        const decrypted = await decryptRSA(priv, str2ab(window.atob(str)));
        return decrypted;
    } catch (error) {
        console.log(error);
    }
}
/**
 * ALlows to generate a key pair for sign and verify with ECDSA alogrithme 256 bits and store it to the localStorage.
 */
async function generateKeyPairSign() {
    let keyPair = await window.crypto.subtle.generateKey(
        {
            name: "ECDSA",
            namedCurve: "P-256",
        },
        true,
        ["sign", "verify"]
    );
    window.crypto.subtle
        .exportKey("jwk", keyPair.privateKey)
        .then((e) =>
            localStorage.setItem("sign_private_key", JSON.stringify(e))
        );
    window.crypto.subtle
        .exportKey("jwk", keyPair.publicKey)
        .then((e) =>
            localStorage.setItem("sign_public_key", JSON.stringify(e))
        );
}

/**
 * Allows to import a public key
 * @param {String} spkiPem the brut key
 * @returns the public key
 */
async function importPublicKey(spkiPem) {
    return await window.crypto.subtle.importKey(
        "spki", // the format for export the RSA public key
        getSpkiDer(spkiPem), // the key data after we remove the header and footer
        {
            name: "RSA-OAEP", // name of public-key encryption system, RSA Optimal Asymmetric Encryption Padding, that use two function of hash
            hash: "SHA-512",
        },
        true, // true, so we can extarct de key with exportKey() or wrapKey()
        ["encrypt"] // usage of the key here for encrypt
    );
}
/**
 * Allows to import a private key
 * @param {String} pkcs8Pem the brut key
 * @returns the private key
 */
async function importPrivateKey(pkcs8Pem) {
    return await window.crypto.subtle.importKey(
        "pkcs8", // the format for export the RSA private key
        getPkcs8DerDecode(pkcs8Pem), // the key data after we remove the header and footer
        {
            name: "RSA-OAEP", // name of public-key encryption system, RSA Optimal Asymmetric Encryption Padding, that use two function of hash
            hash: "SHA-512",
        },
        true, // true, so we can extarct de key with exportKey() or wrapKey()
        ["decrypt"] // usage of the key here for encrypt
    );
}

/**
 * Allows to encrypt a plaintext with RSA and a key
 * @param {String} key the public key
 * @param {String} plaintext the plaint text
 * @returns
 */
async function encryptRSA(key, plaintext) {
    let encrypted = await window.crypto.subtle.encrypt(
        {
            name: "RSA-OAEP", // name of public-key encryption system, RSA Optimal Asymmetric Encryption Padding, that use two function of hash
        },
        key,
        plaintext
    );
    return encrypted;
}
/**
 * Allows to decrypt a plaintext from RSA and a key
 * @param {String} key the private key
 * @param {String} ciphertext the cipher text
 * @returns
 */
async function decryptRSA(key, ciphertext) {
    let decrypted = await window.crypto.subtle.decrypt(
        {
            name: "RSA-OAEP",
        },
        key,
        ciphertext
    );
    return new TextDecoder().decode(decrypted); // decode the data
}

/**
 * Allows you to transform a text key into a spki format
 * @param {String} spkiPem the brut key
 * @returns the key with spki format
 */
function getSpkiDer(spkiPem) {
    const pemHeader = "-----BEGIN PUBLIC KEY-----";
    const pemFooter = "------END PUBLIC KEY-----";

    var pemContents = spkiPem.substring(
        pemHeader.length,
        spkiPem.length - pemFooter.length
    );
    var binaryDerString = window.atob(pemContents); //decode the data has been encoded in base 64
    return str2ab(binaryDerString); // transforme to string
}

/**
 * Allows you to transform a text key into a pkcs8 format
 * @param {String} pkcs8Pem the brut key
 * @returns the key with pkcs8 format
 */
function getPkcs8DerDecode(pkcs8Pem) {
    const pemHeader = "-----BEGIN PRIVATE KEY-----";
    const pemFooter = "-------END PUBLIC KEY-----";
    var pemContents = pkcs8Pem.substring(
        pemHeader.length,
        pkcs8Pem.length - pemFooter.length
    );
    var binaryDerString = window.atob(pemContents); //decode the data has been encoded in base 64
    return str2ab(binaryDerString); // transforme to string
}
/**
 * Allows to transform a string to a array buffer (array of binary data)
 * @param {String} str the string
 * @returns the array buffer
 */
function str2ab(str) {
    const buf = new ArrayBuffer(str.length);
    const bufView = new Uint8Array(buf);
    for (let i = 0, strLen = str.length; i < strLen; i++) {
        bufView[i] = str.charCodeAt(i);
    }
    return buf;
}
/**
 * Allows to transform a array buffer to a string (array of binary data)
 * @param {ArrayBuffer} buf the array buffer
 * @returns the string
 */
function ab2str(buf) {
    return String.fromCharCode.apply(null, new Uint8Array(buf));
}

/**
 * Allows to convert array buffer to base 64
 * @param {String} buffer the array
 * @returns base 64
 */
function arrayBufferToBase64(buffer) {
    var binary = "";
    var bytes = new Uint8Array(buffer);
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}
/**
 * Allows to base 64 to array buffer
 * @param {String} base64 the base 64
 * @returns array buffer
 */
function base64ToArrayBuffer(base64) {
    var binary_string = window.atob(base64);
    var len = binary_string.length;
    var bytes = new Uint8Array(len);
    for (var i = 0; i < len; i++) {
        bytes[i] = binary_string.charCodeAt(i);
    }
    return bytes.buffer;
}
/**
 * Allows to sign a string with a private key
 * @param {String} str the string
 * @returns the signature
 */
async function sign(str) {
    const messageEncode = new TextEncoder().encode(str);
    let sign_private = JSON.parse(localStorage.getItem("sign_private_key"));
    let importKey = await window.crypto.subtle.importKey(
        "jwk",
        sign_private,
        {
            name: "ECDSA",
            namedCurve: "P-256",
        },
        true,
        ["sign"]
    );
    return await window.crypto.subtle.sign(
        {
            name: "ECDSA",
            hash: {
                name: "SHA-256",
            },
        },
        importKey,
        messageEncode
    );
}
/**
 * Allows to verify a signature from a string with a public key
 * @param {String} str the string
 * @param {Array Buffer} sign the signature
 * @param {Key} sign_public_key the key
 * @returns true if that correpond otherwhise false
 */
async function verify(str, sign, sign_public_key) {
    const messageEncode = new TextEncoder().encode(str);
    let sign_public = JSON.parse(sign_public_key);
    let importKey = window.crypto.subtle.importKey(
        "jwk",
        sign_public,
        {
            name: "ECDSA",
            namedCurve: "P-256",
        },
        true,
        ["verify"]
    );
    const public_key = await importKey.then((result) => result);
    return await window.crypto.subtle.verify(
        {
            name: "ECDSA",
            hash: {
                name: "SHA-256",
            },
        },
        public_key,
        sign,
        messageEncode
    );
}
