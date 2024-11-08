# Definir a imagem base do Python
FROM python:3.10

# Definir o diretório de trabalho dentro do contêiner
WORKDIR /bot

# Instalar dependências do sistema para o Google Chrome
RUN apt-get update && apt-get install -y \
    wget \
    unzip \
    gnupg \
    libnss3 \
    libx11-xcb1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxi6 \
    libxtst6 \
    libglib2.0-0 \
    libxrandr2 \
    libgbm1 \
    libasound2 \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libpangocairo-1.0-0 \
    libpango-1.0-0 \
    libcups2 \
    libdrm2 \
    && rm -rf /var/lib/apt/lists/*

# Instalar o Google Chrome
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - && \
    echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list && \
    apt-get update && \
    apt-get install -y google-chrome-stable && \
    rm -rf /var/lib/apt/lists/*

# Copiar o diretório python (onde está o bot.py) para dentro do contêiner
COPY ./python /bot

# Copiar o diretório zap (onde a sessão será salva) para dentro do contêiner
COPY ./zap /zap

# Instalar dependências do Python a partir do requirements.txt
RUN pip install -r /bot/requirements.txt

# Comando para rodar o bot
CMD ["python", "/bot/bot.py"]