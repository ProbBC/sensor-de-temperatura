CREATE TABLE localizacao(
	cod_localizacao		INTEGER	NOT NULL NULL,
    desc_localizacao	VARCHAR(50) NOT NULL,
    CONSTRAINT pk_localizacao
    	PRIMARY KEY (cod_localizacao)
);

CREATE TABLE temperatura(
	cod_temperatura		INTEGER			NOT NULL,
    cod_localizacao		INTEGER			NOT NULL,
    valor_temperatura	DECIMAL(5,2)	NOT NULL,
    data_temperatura	DATE			NOT NULL,
    hora_temperatura	TIME			NOT NULL,
    CONSTRAINT pk_temperatura
    	PRIMARY KEY (cod_temperatura),
    CONSTRAINT fk_temperatura_localizacao
    	FOREIGN KEY (cod_localizacao)
    	REFERENCES localizacao(cod_localizacao)
);

INSERT INTO localizacao (desc_localizacao) VALUES ('Quarto');
INSERT INTO temperatura (cod_localizacao, valor_temperatura, data_temperatura, hora_temperatura) VALUES (0, 24.44, '2019/06/18', '18:36:00');
