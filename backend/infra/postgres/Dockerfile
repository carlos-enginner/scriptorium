FROM postgres:15

RUN apt-get update && apt-get install -y locales-all && apt-get clean && \
    sed -i '/pt_BR.UTF-8/s/^#//g' /etc/locale.gen && \
    locale-gen pt_BR.UTF-8 && \
    update-locale LANG=pt_BR.UTF-8 LC_ALL=pt_BR.UTF-8

ENV LANG=pt_BR.UTF-8
ENV LC_ALL=pt_BR.UTF-8
ENV LC_CTYPE=pt_BR.UTF-8

