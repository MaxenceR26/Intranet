import random



outil = {
    0: 'ciseaux',
    1: 'pierre',
    2: 'feuille'
}

user = input("Choix : ")
nombre = random.randint(0,2)
robot = outil[nombre]
print(user)
print(robot)