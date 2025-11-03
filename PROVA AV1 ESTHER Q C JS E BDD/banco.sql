
CREATE DATABASE IF NOT EXISTS bd_prova_3daw;


USE bd_prova_3daw;

CREATE TABLE IF NOT EXISTS perguntas (
    id_pergunta VARCHAR(50) NOT NULL PRIMARY KEY, 
    tipo ENUM('multipla', 'dissertativa') NOT NULL, 
    pergunta TEXT NOT NULL,                         
    alternativa_a VARCHAR(255),                     
    alternativa_b VARCHAR(255),                     
    alternativa_c VARCHAR(255),                     
    alternativa_d VARCHAR(255),                     
    alternativa_e VARCHAR(255),                     
    resposta_correta TEXT NOT NULL                  
);